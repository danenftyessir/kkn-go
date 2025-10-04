<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectReport;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

/**
 * controller untuk manajemen proyek oleh instansi
 */
class ProjectManagementController extends Controller
{
    /**
     * tampilkan daftar proyek yang sedang berjalan
     */
    public function index(Request $request)
    {
        $institution = auth()->user()->institution;

        $query = Project::with([
            'student.user',
            'student.university',
            'problem',
            'milestones'
        ])
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
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('student.user', function($subq) use ($search) {
                      $subq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'progress':
                $query->orderBy('progress_percentage', 'desc');
                break;
            case 'deadline':
                $query->orderBy('end_date', 'asc');
                break;
            default:
                $query->latest();
        }

        $projects = $query->paginate(15)->withQueryString();

        // statistik proyek
        $stats = [
            'total' => Project::where('institution_id', $institution->id)->count(),
            'planning' => Project::where('institution_id', $institution->id)->where('status', 'planning')->count(),
            'active' => Project::where('institution_id', $institution->id)->where('status', 'active')->count(),
            'review' => Project::where('institution_id', $institution->id)->where('status', 'review')->count(),
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
            'status' => 'required|in:planning,active,review,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $project->update([
            'status' => $validated['status'],
        ]);

        // TODO: kirim notifikasi ke mahasiswa

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
            'due_date' => 'required|date|after:today',
            'deliverables' => 'nullable|array',
            'deliverables.*' => 'string',
        ]);

        $milestone = ProjectMilestone::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'deliverables' => isset($validated['deliverables']) ? json_encode($validated['deliverables']) : null,
            'status' => 'pending',
        ]);

        // TODO: kirim notifikasi ke mahasiswa

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
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'deliverables' => 'nullable|array',
            'deliverables.*' => 'string',
        ]);

        $milestone->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'deliverables' => isset($validated['deliverables']) ? json_encode($validated['deliverables']) : null,
        ]);

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
     * approve report mahasiswa
     */
    public function approveReport(Request $request, $id, $reportId)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);
        $report = ProjectReport::where('project_id', $project->id)->findOrFail($reportId);

        $validated = $request->validate([
            'feedback' => 'nullable|string|max:1000',
        ]);

        $report->update([
            'status' => 'approved',
            'institution_feedback' => $validated['feedback'] ?? null,
            'reviewed_at' => now(),
        ]);

        // TODO: kirim notifikasi ke mahasiswa

        return back()->with('success', 'Report berhasil disetujui!');
    }

    /**
     * reject report mahasiswa dengan feedback
     */
    public function rejectReport(Request $request, $id, $reportId)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);
        $report = ProjectReport::where('project_id', $project->id)->findOrFail($reportId);

        $validated = $request->validate([
            'feedback' => 'required|string|max:1000',
        ]);

        $report->update([
            'status' => 'revision_required',
            'institution_feedback' => $validated['feedback'],
            'reviewed_at' => now(),
        ]);

        // TODO: kirim notifikasi ke mahasiswa

        return back()->with('success', 'Report dikembalikan untuk revisi.');
    }

    /**
     * submit review untuk mahasiswa setelah proyek selesai
     */
    public function submitReview(Request $request, $id)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($id);

        // hanya bisa review jika proyek sudah selesai
        if ($project->status !== 'completed') {
            return back()->with('error', 'Hanya dapat memberikan review untuk proyek yang sudah selesai.');
        }

        // cek apakah sudah pernah review
        if ($project->rating) {
            return back()->with('error', 'Anda sudah memberikan review untuk proyek ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // update rating di project
            $project->update([
                'rating' => $validated['rating'],
                'institution_review' => $validated['review'],
                'reviewed_at' => now(),
            ]);

            // buat review record
            Review::create([
                'project_id' => $project->id,
                'reviewer_id' => auth()->id(),
                'reviewer_type' => 'institution',
                'student_id' => $project->student_id,
                'rating' => $validated['rating'],
                'review' => $validated['review'],
            ]);

            // TODO: kirim notifikasi ke mahasiswa

            DB::commit();

            return back()->with('success', 'Review berhasil diberikan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * private helper untuk update progress percentage proyek
     */
    private function updateProjectProgress($project)
    {
        $totalMilestones = $project->milestones()->count();
        $completedMilestones = $project->milestones()->where('status', 'completed')->count();
        
        $percentage = $totalMilestones > 0 ? ($completedMilestones / $totalMilestones) * 100 : 0;

        $project->update(['progress_percentage' => round($percentage, 2)]);
    }
}