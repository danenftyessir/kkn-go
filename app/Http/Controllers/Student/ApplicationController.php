<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Problem;
use App\Services\NotificationService;
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
        \App\Services\SupabaseStorageService $storageService
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
                $filename = 'proposal-' . $student->id . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = 'proposals/' . $filename;
                
                $proposalPath = $this->storageService->uploadFile($file, $path);
                
                if (!$proposalPath) {
                    throw new \Exception('Gagal upload proposal ke storage');
                }
                
                Log::info("Proposal berhasil diupload: {$proposalPath}");
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
            
            // increment counter aplikasi di problem
            $problem->increment('applications_count');
            
            // kirim notifikasi ke instansi
            try {
                $this->notificationService->applicationSubmitted($application);
                Log::info("Notifikasi aplikasi berhasil dikirim ke instansi ID: {$problem->institution_id}");
            } catch (\Exception $e) {
                Log::error("Gagal mengirim notifikasi aplikasi: " . $e->getMessage());
                // jangan batalkan transaksi hanya karena notifikasi gagal
            }
            
            DB::commit();
            
            return redirect()
                ->route('student.applications.show', $application->id)
                ->with('success', 'Aplikasi berhasil dikirim! Instansi akan meninjau aplikasi Anda.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Error saat menyimpan aplikasi: " . $e->getMessage());
            
            // hapus file proposal dari supabase jika ada error
            if (isset($proposalPath) && $proposalPath) {
                try {
                    $this->storageService->delete($proposalPath);
                } catch (\Exception $deleteError) {
                    Log::error("Gagal menghapus proposal: " . $deleteError->getMessage());
                }
            }
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengirim aplikasi. Silakan coba lagi.');
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
                    $this->storageService->delete($application->proposal_path);
                    Log::info("Proposal berhasil dihapus: {$application->proposal_path}");
                } catch (\Exception $e) {
                    Log::error("Gagal menghapus proposal: " . $e->getMessage());
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
            
            Log::error("Error saat membatalkan aplikasi: " . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan saat membatalkan aplikasi.');
        }
    }
}