<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * controller untuk mengelola proyek yang sedang berjalan
 * instansi dapat monitor progress, manage milestones, dan berikan feedback
 */
class ProjectManagementController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * tampilkan daftar semua proyek
     */
    public function index(Request $request)
    {
        $institution = auth()->user()->institution;

        $query = Project::with(['student.user', 'student.university', 'problem'])
                       ->where('institution_id', $institution->id);

        // filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // filter berdasarkan problem
        if ($request->filled('problem_id')) {
            $query->where('problem_id', $request->problem_id);
        }

        // search berdasarkan nama mahasiswa atau judul
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhereHas('student.user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(15);

        // hitung jumlah project berdasarkan status untuk summary cards
        $baseQuery = Project::where('institution_id', $institution->id);

        // statistik untuk summary cards
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'planning' => 0, // fix: tambahkan key 'planning' dengan value 0 karena tidak ada status planning di database
            'active' => (clone $baseQuery)->where('status', 'active')->count(),
            'review' => 0, // fix: tambahkan key 'review' dengan value 0 karena tidak ada status review di database
            'on_hold' => (clone $baseQuery)->where('status', 'on_hold')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        // daftar problems untuk filter dropdown
        $problems = $institution->problems()->orderBy('title')->get(['id', 'title']);

        return view('institution.projects.index', compact('projects', 'stats', 'problems'));
    }

    /**
     * tampilkan detail proyek untuk monitoring
     */
    public function show($id)
    {
        $institution = auth()->user()->institution;

        $project = Project::with([
            'student.user',
            'student.university',
            'problem.images',
            'problem.province',
            'problem.regency',
            'milestones',
            'reports.student.user'
        ])
        ->where('institution_id', $institution->id)
        ->findOrFail($id);

        // hitung progress
        $totalMilestones = $project->milestones()->count();
        $completedMilestones = $project->milestones()->where('status', 'completed')->count();
        $progressPercentage = $totalMilestones > 0 ? ($completedMilestones / $totalMilestones) * 100 : 0;

        return view('institution.projects.show', compact('project', 'progressPercentage'));
    }

    /**
     * tampilkan halaman manage proyek (milestone, feedback, dll)
     */
    public function manage($id)
    {
        $institution = auth()->user()->institution;

        $project = Project::with([
            'student.user',
            'student.university',
            'problem',
            'milestones',
            'reports'
        ])
        ->where('institution_id', $institution->id)
        ->findOrFail($id);

        return view('institution.projects.manage', compact('project'));
    }

    /**
     * update status proyek
     */
    public function updateStatus(Request $request, $id)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:active,on_hold,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        // jika status berubah menjadi completed, set completed_at
        $data = ['status' => $validated['status']];
        if ($validated['status'] === 'completed' && $project->status !== 'completed') {
            $data['completed_at'] = now();
        }

        $project->update($data);

        return response()->json([
            'success' => true,
            'message' => 'status proyek berhasil diubah',
        ]);
    }

    /**
     * tambah milestone baru
     */
    public function addMilestone(Request $request, $id)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'target_date' => 'required|date|after:today',
            'deliverables' => 'nullable|string',
        ]);

        // hitung order
        $maxOrder = $project->milestones()->max('order') ?? 0;

        $milestone = ProjectMilestone::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'target_date' => $validated['target_date'],
            'deliverables' => $validated['deliverables'] ? explode(',', $validated['deliverables']) : null,
            'order' => $maxOrder + 1,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'milestone berhasil ditambahkan',
            'milestone' => $milestone,
        ]);
    }

    /**
     * update milestone
     */
    public function updateMilestone(Request $request, $id, $milestoneId)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);
        $milestone = $project->milestones()->findOrFail($milestoneId);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'target_date' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,completed,delayed',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // jika status berubah menjadi completed
        if (isset($validated['status']) && $validated['status'] === 'completed' && $milestone->status !== 'completed') {
            $validated['completed_at'] = now();
            $validated['progress_percentage'] = 100;
        }

        $milestone->update($validated);

        // update progress proyek
        $this->projectService->updateProjectProgress($project->id);

        return response()->json([
            'success' => true,
            'message' => 'milestone berhasil diupdate',
            'milestone' => $milestone->fresh(),
        ]);
    }

    /**
     * delete milestone
     */
    public function deleteMilestone($id, $milestoneId)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);
        $milestone = $project->milestones()->findOrFail($milestoneId);

        // tidak bisa delete milestone yang sudah completed
        if ($milestone->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'tidak dapat menghapus milestone yang sudah selesai',
            ], 400);
        }

        $milestone->delete();

        // update progress proyek
        $this->projectService->updateProjectProgress($project->id);

        return response()->json([
            'success' => true,
            'message' => 'milestone berhasil dihapus',
        ]);
    }

    /**
     * berikan feedback untuk report
     */
    public function giveFeedback(Request $request, $id, $reportId)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);
        $report = $project->reports()->findOrFail($reportId);

        $validated = $request->validate([
            'feedback' => 'required|string|max:1000',
            'status' => 'required|in:approved,revision_needed',
        ]);

        $report->update([
            'institution_feedback' => $validated['feedback'],
            'status' => $validated['status'],
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'feedback berhasil diberikan',
        ]);
    }

    /**
     * approve final report
     */
    public function approveFinalReport(Request $request, $id)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);

        // pastikan project sudah completed
        if ($project->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'proyek belum selesai',
            ], 400);
        }

        $validated = $request->validate([
            'feedback' => 'nullable|string|max:1000',
        ]);

        $project->update([
            'final_report_approved' => true,
            'final_report_feedback' => $validated['feedback'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'laporan akhir berhasil disetujui',
        ]);
    }
}