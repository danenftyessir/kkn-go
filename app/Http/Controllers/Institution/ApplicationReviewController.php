<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Problem;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * controller untuk mengelola review aplikasi mahasiswa
 * instansi dapat melihat, menerima, atau menolak aplikasi yang masuk
 */
class ApplicationReviewController extends Controller
{
    /**
     * tampilkan daftar aplikasi yang masuk
     */
    public function index(Request $request)
    {
        $institution = auth()->user()->institution;

        $query = Application::with(['student.user', 'student.university', 'problem'])
                            ->whereHas('problem', function($q) use ($institution) {
                                $q->where('institution_id', $institution->id);
                            });

        // filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // filter berdasarkan problem
        if ($request->filled('problem_id')) {
            $query->where('problem_id', $request->problem_id);
        }

        // search berdasarkan nama mahasiswa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $applications = $query->orderBy('applied_at', 'desc')->paginate(15);

        // FIXED: statistik untuk summary cards dengan key yang lengkap
        $stats = [
            'total' => Application::whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })->count(),
            'pending' => Application::whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })->where('status', 'pending')->count(),
            'under_review' => Application::whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })->where('status', 'reviewed')->count(),
            'accepted' => Application::whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })->where('status', 'accepted')->count(),
            'rejected' => Application::whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })->where('status', 'rejected')->count(),
        ];

        // daftar problems untuk filter dropdown
        $problems = $institution->problems()->orderBy('title')->get(['id', 'title']);

        return view('institution.applications.index', compact('applications', 'stats', 'problems'));
    }

    /**
     * tampilkan detail aplikasi untuk review
     */
    public function show($id)
    {
        $institution = auth()->user()->institution;

        $application = Application::with([
            'student.user',
            'student.university',
            'problem.images',
            'problem.province',
            'problem.regency'
        ])
        ->whereHas('problem', function($q) use ($institution) {
            $q->where('institution_id', $institution->id);
        })
        ->findOrFail($id);

        // tandai sebagai sudah direview (update status ke reviewed jika masih pending)
        if ($application->status === 'pending') {
            $application->update(['status' => 'reviewed']);
        }

        return view('institution.applications.show', compact('application'));
    }

    /**
     * tampilkan form review aplikasi
     */
    public function review($id)
    {
        $institution = auth()->user()->institution;

        $application = Application::with([
            'student.user',
            'student.university',
            'problem'
        ])
        ->whereHas('problem', function($q) use ($institution) {
            $q->where('institution_id', $institution->id);
        })
        ->findOrFail($id);

        // hanya bisa review jika status pending atau reviewed
        if (!in_array($application->status, ['pending', 'reviewed'])) {
            return redirect()->route('institution.applications.show', $application->id)
                           ->with('error', 'Aplikasi ini sudah diproses.');
        }

        return view('institution.applications.review', compact('application'));
    }

    /**
     * terima aplikasi mahasiswa
     */
    public function accept(Request $request, $id)
    {
        $institution = auth()->user()->institution;

        $application = Application::with('problem')
            ->whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })
            ->findOrFail($id);

        // validasi
        if (!in_array($application->status, ['pending', 'reviewed'])) {
            return back()->with('error', 'Aplikasi ini sudah diproses.');
        }

        // cek apakah masih ada slot tersedia
        $problem = $application->problem;
        $acceptedCount = $problem->applications()->where('status', 'accepted')->count();
        
        if ($acceptedCount >= $problem->required_students) {
            return back()->with('error', 'Slot mahasiswa untuk masalah ini sudah penuh.');
        }

        $validated = $request->validate([
            'feedback' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // update application
            $application->update([
                'status' => 'accepted',
                'reviewed_at' => now(),
                'feedback' => $validated['feedback'] ?? null,
                'institution_notes' => $validated['notes'] ?? null,
            ]);

            // buat project baru
            $project = Project::create([
                'problem_id' => $application->problem_id,
                'student_id' => $application->student_id,
                'institution_id' => $institution->id,
                'title' => $application->problem->title,
                'description' => $application->problem->description,
                'status' => 'active',
                'start_date' => $application->problem->start_date ?? now(),
                'end_date' => $application->problem->end_date ?? now()->addMonths(2),
                'role_in_team' => $application->proposed_role ?? 'Anggota Tim',
            ]);

            // kirim notifikasi ke mahasiswa
            $application->student->user->notifications()->create([
                'type' => 'application_accepted',
                'title' => 'Aplikasi Diterima!',
                'message' => "Selamat! Aplikasi Anda untuk '{$application->problem->title}' telah diterima oleh {$institution->name}.",
                'data' => [
                    'application_id' => $application->id,
                    'project_id' => $project->id,
                    'problem_id' => $application->problem_id,
                ],
            ]);

            DB::commit();

            return redirect()->route('institution.applications.index')
                           ->with('success', 'Aplikasi berhasil diterima dan proyek telah dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * tolak aplikasi mahasiswa
     */
    public function reject(Request $request, $id)
    {
        $institution = auth()->user()->institution;

        $application = Application::with('problem')
            ->whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })
            ->findOrFail($id);

        // validasi
        if (!in_array($application->status, ['pending', 'reviewed'])) {
            return back()->with('error', 'Aplikasi ini sudah diproses.');
        }

        $validated = $request->validate([
            'feedback' => 'required|string|max:1000',
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // update application
            $application->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
                'feedback' => $validated['feedback'],
                'institution_notes' => $validated['rejection_reason'] ?? null,
            ]);

            // kirim notifikasi ke mahasiswa
            $application->student->user->notifications()->create([
                'type' => 'application_rejected',
                'title' => 'Aplikasi Ditolak',
                'message' => "Maaf, aplikasi Anda untuk '{$application->problem->title}' tidak dapat diterima saat ini.",
                'data' => [
                    'application_id' => $application->id,
                    'problem_id' => $application->problem_id,
                ],
            ]);

            DB::commit();

            return redirect()->route('institution.applications.index')
                           ->with('success', 'Aplikasi berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}