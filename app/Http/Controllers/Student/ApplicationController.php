<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Problem;
use App\Services\NotificationService;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * controller untuk mengelola aplikasi mahasiswa ke problems
 * mahasiswa dapat apply, view status, dan withdraw aplikasi
 * 
 * path: app/Http/Controllers/Student/ApplicationController.php
 */
class ApplicationController extends Controller
{
    protected $notificationService;
    protected $storageService;

    public function __construct(
        NotificationService $notificationService,
        SupabaseStorageService $storageService
    ) {
        $this->notificationService = $notificationService;
        $this->storageService = $storageService;
    }

    /**
     * tampilkan daftar semua aplikasi mahasiswa
     */
    public function index(Request $request)
    {
        $student = Auth::user()->student;
        
        $query = Application::with(['problem.institution', 'problem.province', 'problem.regency'])
                            ->where('student_id', $student->id);
        
        // filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $applications = $query->orderBy('applied_at', 'desc')->paginate(10);
        
        // statistik untuk summary
        $stats = [
            'total' => Application::where('student_id', $student->id)->count(),
            'pending' => Application::where('student_id', $student->id)->where('status', 'pending')->count(),
            'reviewed' => Application::where('student_id', $student->id)->where('status', 'reviewed')->count(),
            'accepted' => Application::where('student_id', $student->id)->where('status', 'accepted')->count(),
            'rejected' => Application::where('student_id', $student->id)->where('status', 'rejected')->count(),
        ];
        
        return view('student.applications.index', compact('applications', 'stats'));
    }
    
    /**
     * tampilkan form untuk apply ke problem
     */
    public function create($problemId)
    {
        $problem = Problem::with(['institution', 'province', 'regency'])
                         ->findOrFail($problemId);
        
        // validasi problem masih open
        if ($problem->status !== 'open') {
            return redirect()
                ->route('student.browse-problems.index')
                ->with('error', 'Maaf, proyek ini sudah tidak menerima aplikasi');
        }
        
        // validasi deadline belum lewat
        if ($problem->application_deadline < now()) {
            return redirect()
                ->route('student.browse-problems.detail', $problem->id)
                ->with('error', 'Maaf, deadline aplikasi sudah berakhir');
        }
        
        // cek apakah sudah pernah apply
        $student = Auth::user()->student;
        $hasApplied = Application::where('student_id', $student->id)
                                ->where('problem_id', $problem->id)
                                ->exists();
        
        if ($hasApplied) {
            return redirect()
                ->route('student.browse-problems.detail', $problem->id)
                ->with('info', 'Anda sudah mengajukan aplikasi untuk proyek ini');
        }
        
        return view('student.applications.create', compact('problem'));
    }
    
    /**
     * simpan aplikasi baru
     * SUPABASE ONLY - NO FALLBACK
     */
    public function store(Request $request)
    {
        $student = Auth::user()->student;
        
        // validasi input
        $validated = $request->validate([
            'problem_id' => 'required|exists:problems,id',
            'motivation' => 'required|string|min:100',
            'proposal' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // proposal opsional, max 5MB
        ], [
            'motivation.required' => 'Motivasi wajib diisi',
            'motivation.min' => 'Motivasi minimal 100 karakter',
            'proposal.mimes' => 'Proposal harus berformat PDF, DOC, atau DOCX',
            'proposal.max' => 'Ukuran proposal maksimal 5MB',
        ]);
        
        $problem = Problem::findOrFail($validated['problem_id']);
        
        // validasi problem masih open dan deadline belum lewat
        if ($problem->status !== 'open' || $problem->application_deadline < now()) {
            return back()->with('error', 'Proyek sudah tidak menerima aplikasi');
        }
        
        // cek apakah sudah pernah apply
        $hasApplied = Application::where('student_id', $student->id)
                                ->where('problem_id', $problem->id)
                                ->exists();
        
        if ($hasApplied) {
            return back()->with('error', 'Anda sudah mengajukan aplikasi untuk proyek ini');
        }
        
        try {
            DB::beginTransaction();
            
            $proposalPath = null;
            
            // upload proposal ke supabase jika ada
            if ($request->hasFile('proposal')) {
                $file = $request->file('proposal');
                
                // generate filename yang unique
                $extension = $file->getClientOriginalExtension();
                $filename = 'proposal-' . $student->id . '-' . time() . '-' . uniqid() . '.' . $extension;
                $supabasePath = 'proposals/' . $filename;
                
                Log::info("🚀 START Upload Proposal", [
                    'student_id' => $student->id,
                    'filename' => $filename,
                    'path' => $supabasePath,
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]);
                
                // upload ke supabase - MUST SUCCEED
                $proposalPath = $this->storageService->uploadFile($file, $supabasePath);
                
                if (!$proposalPath) {
                    Log::error("❌ UPLOAD FAILED: Supabase uploadFile returned false");
                    throw new \Exception('Gagal mengupload proposal ke Supabase Storage. Silakan coba lagi.');
                }
                
                Log::info("✅ SUCCESS Upload Proposal", [
                    'uploaded_path' => $proposalPath,
                    'public_url' => $this->storageService->getPublicUrl($proposalPath),
                ]);
                
                // verify file exists di supabase
                if (!$this->storageService->exists($proposalPath)) {
                    Log::error("❌ VERIFICATION FAILED: File tidak ditemukan setelah upload", [
                        'path' => $proposalPath,
                    ]);
                    throw new \Exception('File berhasil diupload namun tidak dapat diverifikasi. Silakan coba lagi.');
                }
                
                Log::info("✅ VERIFIED: File exists di Supabase", [
                    'path' => $proposalPath,
                ]);
            }
            
            // simpan aplikasi
            $application = Application::create([
                'student_id' => $student->id,
                'problem_id' => $problem->id,
                'motivation' => $validated['motivation'],
                'proposal_path' => $proposalPath,
                'status' => 'pending',
                'applied_at' => now(),
            ]);
            
            Log::info("✅ Application created", [
                'application_id' => $application->id,
                'proposal_path' => $proposalPath,
            ]);
            
            // increment counter aplikasi di problem
            $problem->increment('applications_count');
            
            // kirim notifikasi ke instansi
            try {
                $this->notificationService->applicationSubmitted($application);
                Log::info("✅ Notifikasi berhasil dikirim", [
                    'institution_id' => $problem->institution_id,
                ]);
            } catch (\Exception $e) {
                Log::error("⚠️ Gagal kirim notifikasi (non-critical): " . $e->getMessage());
                // tidak perlu rollback untuk error notifikasi
            }
            
            DB::commit();
            
            Log::info("✅ COMPLETE: Aplikasi berhasil disimpan", [
                'application_id' => $application->id,
            ]);
            
            return redirect()
                ->route('student.applications.show', $application->id)
                ->with('success', 'Aplikasi berhasil dikirim! Instansi akan meninjau aplikasi Anda.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("❌ ERROR: Gagal menyimpan aplikasi", [
                'student_id' => $student->id,
                'problem_id' => $problem->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // cleanup file dari supabase jika ada
            if (isset($proposalPath) && $proposalPath) {
                try {
                    $this->storageService->delete($proposalPath);
                    Log::info("🗑️ Cleanup: File proposal berhasil dihapus setelah error");
                } catch (\Exception $deleteError) {
                    Log::error("⚠️ Cleanup failed: " . $deleteError->getMessage());
                }
            }
            
            // return error message yang jelas
            $errorMessage = 'Terjadi kesalahan saat mengirim aplikasi. ';
            
            if (str_contains($e->getMessage(), 'Supabase')) {
                $errorMessage .= 'Masalah koneksi dengan storage server. Silakan coba lagi dalam beberapa saat.';
            } else {
                $errorMessage .= $e->getMessage();
            }
            
            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }
    
    /**
     * tampilkan detail aplikasi
     */
    public function show($id)
    {
        $student = Auth::user()->student;
        
        $application = Application::with([
            'problem.institution',
            'problem.province',
            'problem.regency',
            'problem.images'
        ])
        ->where('id', $id)
        ->where('student_id', $student->id)
        ->firstOrFail();
        
        return view('student.applications.show', compact('application'));
    }
    
    /**
     * withdraw/batalkan aplikasi
     * hanya bisa untuk aplikasi dengan status pending
     */
    public function destroy($id)
    {
        $student = Auth::user()->student;
        
        $application = Application::where('id', $id)
                                 ->where('student_id', $student->id)
                                 ->firstOrFail();
        
        // hanya bisa withdraw jika status masih pending
        if ($application->status !== 'pending') {
            return back()->with('error', 'Aplikasi tidak dapat dibatalkan karena sudah diproses.');
        }
        
        try {
            DB::beginTransaction();
            
            // hapus file proposal dari supabase jika ada
            if ($application->proposal_path) {
                try {
                    $deleted = $this->storageService->delete($application->proposal_path);
                    
                    if ($deleted) {
                        Log::info("✅ Proposal berhasil dihapus dari Supabase", [
                            'path' => $application->proposal_path,
                        ]);
                    } else {
                        Log::warning("⚠️ File proposal mungkin sudah tidak ada", [
                            'path' => $application->proposal_path,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error("❌ Gagal menghapus proposal: " . $e->getMessage());
                    // tidak perlu rollback jika gagal hapus file
                }
            }
            
            // decrement counter aplikasi di problem
            $application->problem->decrement('applications_count');
            
            // hapus aplikasi
            $application->delete();
            
            DB::commit();
            
            return redirect()
                ->route('student.applications.index')
                ->with('success', 'Aplikasi berhasil dibatalkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("❌ Error saat membatalkan aplikasi: " . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan saat membatalkan aplikasi.');
        }
    }
}