<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Problem;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

/**
 * controller untuk review aplikasi mahasiswa oleh instansi
 */
class ApplicationReviewController extends Controller
{
    /**
     * tampilkan daftar aplikasi yang perlu direview
     */
    public function index(Request $request)
    {
        $institution = auth()->user()->institution;

        $query = Application::with([
            'student.user',
            'student.university',
            'problem'
        ])
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
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'problem':
                $query->join('problems', 'applications.problem_id', '=', 'problems.id')
                      ->orderBy('problems.title');
                break;
            default:
                $query->latest();
        }

        $applications = $query->paginate(15)->withQueryString();

        // statistik aplikasi
        $stats = [
            'total' => Application::whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })->count(),
            'pending' => Application::whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })->where('status', 'pending')->count(),
            'under_review' => Application::whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })->where('status', 'under_review')->count(),
            'accepted' => Application::whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })->where('status', 'accepted')->count(),
            'rejected' => Application::whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })->where('status', 'rejected')->count(),
        ];

        // daftar problems untuk filter dropdown
        $problems = Problem::where('institution_id', $institution->id)
                          ->orderBy('title')
                          ->get(['id', 'title']);

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

        // tandai sebagai sudah direview (update status ke under_review jika masih pending)
        if ($application->status === 'pending') {
            $application->update(['status' => 'under_review']);
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

        // hanya bisa review jika status pending atau under_review
        if (!in_array($application->status, ['pending', 'under_review'])) {
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
        if (!in_array($application->status, ['pending', 'under_review'])) {
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

            // buat project otomatis
            Project::create([
                'student_id' => $application->student_id,
                'problem_id' => $application->problem_id,
                'institution_id' => $institution->id,
                'title' => $problem->title,
                'description' => $problem->description,
                'start_date' => $problem->start_date,
                'end_date' => $problem->end_date,
                'status' => 'active',
                'role_in_team' => 'Anggota Tim', // default role
            ]);

            // update counter problem
            $problem->increment('accepted_students');

            // TODO: kirim notifikasi ke mahasiswa (email + in-app)

            DB::commit();

            return redirect()->route('institution.applications.show', $application->id)
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
        if (!in_array($application->status, ['pending', 'under_review'])) {
            return back()->with('error', 'Aplikasi ini sudah diproses.');
        }

        $validated = $request->validate([
            'feedback' => 'required|string|max:1000',
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // update application
            $application->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
                'feedback' => $validated['feedback'],
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            // TODO: kirim notifikasi ke mahasiswa (email + in-app)

            DB::commit();

            return redirect()->route('institution.applications.show', $application->id)
                           ->with('success', 'Aplikasi telah ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * batalkan keputusan review (kembalikan ke pending)
     */
    public function cancel($id)
    {
        $institution = auth()->user()->institution;

        $application = Application::with('problem')
            ->whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })
            ->findOrFail($id);

        // hanya bisa cancel jika rejected atau accepted (dan belum ada project yang aktif)
        if (!in_array($application->status, ['rejected', 'accepted'])) {
            return back()->with('error', 'Tidak dapat membatalkan review untuk aplikasi ini.');
        }

        // jika accepted, cek apakah sudah ada project
        if ($application->status === 'accepted') {
            $project = Project::where('student_id', $application->student_id)
                             ->where('problem_id', $application->problem_id)
                             ->where('status', 'active')
                             ->first();
            
            if ($project) {
                return back()->with('error', 'Tidak dapat membatalkan karena proyek sudah berjalan.');
            }
        }

        try {
            DB::beginTransaction();

            // kembalikan ke pending
            $application->update([
                'status' => 'pending',
                'reviewed_at' => null,
                'feedback' => null,
                'rejection_reason' => null,
                'institution_notes' => null,
            ]);

            // jika ada project, hapus
            Project::where('student_id', $application->student_id)
                  ->where('problem_id', $application->problem_id)
                  ->where('status', 'planning')
                  ->delete();

            // update counter problem jika sebelumnya accepted
            if ($application->status === 'accepted') {
                $application->problem->decrement('accepted_students');
            }

            DB::commit();

            return redirect()->route('institution.applications.show', $application->id)
                           ->with('success', 'Review berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * bulk action untuk multiple applications
     */
    public function bulkAction(Request $request)
    {
        $institution = auth()->user()->institution;

        $validated = $request->validate([
            'action' => 'required|in:accept,reject,delete',
            'application_ids' => 'required|array|min:1',
            'application_ids.*' => 'required|exists:applications,id',
            'feedback' => 'required_if:action,reject|string|max:1000',
            'rejection_reason' => 'required_if:action,reject|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $applications = Application::whereIn('id', $validated['application_ids'])
                ->whereHas('problem', function($q) use ($institution) {
                    $q->where('institution_id', $institution->id);
                })
                ->get();

            $successCount = 0;
            $errorCount = 0;

            foreach ($applications as $application) {
                try {
                    switch ($validated['action']) {
                        case 'accept':
                            // cek slot
                            $acceptedCount = $application->problem->applications()
                                ->where('status', 'accepted')->count();
                            
                            if ($acceptedCount >= $application->problem->required_students) {
                                $errorCount++;
                                continue 2;
                            }

                            $application->update([
                                'status' => 'accepted',
                                'reviewed_at' => now(),
                            ]);

                            // buat project
                            Project::create([
                                'student_id' => $application->student_id,
                                'problem_id' => $application->problem_id,
                                'institution_id' => $institution->id,
                                'title' => $application->problem->title,
                                'description' => $application->problem->description,
                                'start_date' => $application->problem->start_date,
                                'end_date' => $application->problem->end_date,
                                'status' => 'active',
                                'role_in_team' => 'Anggota Tim',
                            ]);

                            $application->problem->increment('accepted_students');
                            $successCount++;
                            break;

                        case 'reject':
                            $application->update([
                                'status' => 'rejected',
                                'reviewed_at' => now(),
                                'feedback' => $validated['feedback'],
                                'rejection_reason' => $validated['rejection_reason'],
                            ]);
                            $successCount++;
                            break;

                        case 'delete':
                            // hanya bisa delete jika pending atau rejected
                            if (in_array($application->status, ['pending', 'rejected'])) {
                                $application->delete();
                                $successCount++;
                            } else {
                                $errorCount++;
                            }
                            break;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    continue;
                }
            }

            DB::commit();

            $message = "{$successCount} aplikasi berhasil diproses.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} aplikasi gagal diproses.";
            }

            return redirect()->route('institution.applications.index')
                           ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}