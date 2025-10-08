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

        // statistik untuk summary cards
        $stats = [
            'total' => Project::where('institution_id', $institution->id)->count(),
            'active' => Project::where('institution_id', $institution->id)->where('status', 'active')->count(),
            'on_hold' => Project::where('institution_id', $institution->id)->where('status', 'on_hold')->count(),
            'completed' => Project::where('institution_id', $institution->id)->where('status', 'completed')->count(),
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
            'message' => 'Status proyek berhasil diubah!',
            'status' => $project->status
        ]);
    }

    /**
     * tambah milestone untuk proyek
     */
    public function addMilestone(Request $request, $id)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_date' => 'required|date|after:today',
            'deliverables' => 'nullable|array',
            'deliverables.*' => 'string',
        ]);

        $milestone = ProjectMilestone::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'target_date' => $validated['target_date'],
            'deliverables' => isset($validated['deliverables']) ? json_encode($validated['deliverables']) : null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Milestone berhasil ditambahkan!');
    }

    /**
     * update milestone
     */
    public function updateMilestone(Request $request, $id, $milestoneId)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);
        $milestone = ProjectMilestone::where('project_id', $project->id)->findOrFail($milestoneId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed,delayed',
            'deliverables' => 'nullable|array',
            'deliverables.*' => 'string',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'target_date' => $validated['target_date'],
            'status' => $validated['status'],
            'deliverables' => isset($validated['deliverables']) ? json_encode($validated['deliverables']) : null,
        ];

        // jika status berubah menjadi completed, set completed_at
        if ($validated['status'] === 'completed' && $milestone->status !== 'completed') {
            $data['completed_at'] = now();
        }

        $milestone->update($data);

        // update progress percentage proyek
        $this->updateProjectProgress($project);

        return back()->with('success', 'Milestone berhasil diperbarui!');
    }

    /**
     * hapus milestone
     */
    public function deleteMilestone($id, $milestoneId)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);
        $milestone = ProjectMilestone::where('project_id', $project->id)->findOrFail($milestoneId);

        $milestone->delete();

        // update progress percentage proyek
        $this->updateProjectProgress($project);

        return back()->with('success', 'Milestone berhasil dihapus!');
    }

    /**
     * update progress percentage proyek berdasarkan milestone
     * 
     * @param Project $project
     */
    protected function updateProjectProgress(Project $project)
    {
        $totalMilestones = $project->milestones()->count();
        
        if ($totalMilestones === 0) {
            $project->update(['progress_percentage' => 0]);
            return;
        }

        $completedMilestones = $project->milestones()->where('status', 'completed')->count();
        $progressPercentage = ($completedMilestones / $totalMilestones) * 100;

        $project->update(['progress_percentage' => round($progressPercentage)]);
    }
}