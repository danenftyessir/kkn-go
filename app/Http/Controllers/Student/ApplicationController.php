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
 * FILE DISIMPAN DI DATABASE (tidak pakai Supabase Storage)
 * 
 * path: app/Http/Controllers/Student/ApplicationController.php
 */
class ApplicationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
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
     * FILE DISIMPAN LANGSUNG DI DATABASE
     */
    public function store(Request $request)
    {
        $student = Auth::user()->student;
        
        // validasi input
        $validated = $request->validate([
            'problem_id' => 'required|exists:problems,id',
            'motivation' => 'required|string|min:100',
            'proposal' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // max 5MB
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
            
            $proposalData = [];
            
            // simpan file proposal ke database jika ada
            if ($request->hasFile('proposal')) {
                $file = $request->file('proposal');
                
                Log::info("📄 Processing proposal file", [
                    'student_id' => $student->id,
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]);
                
                // baca file content sebagai base64
                $fileContent = base64_encode(file_get_contents($file->getRealPath()));
                
                $proposalData = [
                    'proposal_content' => $fileContent,
                    'proposal_filename' => $file->getClientOriginalName(),
                    'proposal_mime_type' => $file->getMimeType(),
                    'proposal_size' => $file->getSize(),
                ];
                
                Log::info("✅ File proposal berhasil diproses", [
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize() . ' bytes',
                ]);
            }
            
            // simpan aplikasi
            $application = Application::create([
                'student_id' => $student->id,
                'problem_id' => $problem->id,
                'motivation' => $validated['motivation'],
                'status' => 'pending',
                'applied_at' => now(),
                ...$proposalData, // merge proposal data jika ada
            ]);
            
            Log::info("✅ Application created", [
                'application_id' => $application->id,
                'has_proposal' => !empty($proposalData),
            ]);
            
            // increment counter aplikasi di problem
            $problem->increment('applications_count');
            
            // kirim notifikasi ke instansi
            try {
                $this->notificationService->applicationSubmitted($application);
                Log::info("✅ Notifikasi berhasil dikirim");
            } catch (\Exception $e) {
                Log::error("⚠️ Gagal kirim notifikasi: " . $e->getMessage());
            }
            
            DB::commit();
            
            return redirect()
                ->route('student.applications.show', $application->id)
                ->with('success', 'Aplikasi berhasil dikirim! Instansi akan meninjau aplikasi Anda.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("❌ Error saat menyimpan aplikasi", [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
     * download proposal dari database
     */
    public function downloadProposal($id)
    {
        $student = Auth::user()->student;
        
        $application = Application::where('id', $id)
                                 ->where('student_id', $student->id)
                                 ->firstOrFail();
        
        // cek apakah ada proposal
        if (!$application->proposal_content) {
            abort(404, 'Proposal tidak ditemukan');
        }
        
        // decode base64 content
        $fileContent = base64_decode($application->proposal_content);
        
        // return file sebagai download
        return response($fileContent)
            ->header('Content-Type', $application->proposal_mime_type)
            ->header('Content-Disposition', 'inline; filename="' . $application->proposal_filename . '"')
            ->header('Content-Length', strlen($fileContent));
    }
    
    /**
     * withdraw/batalkan aplikasi
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
            
            // decrement counter aplikasi di problem
            $application->problem->decrement('applications_count');
            
            // hapus aplikasi (file proposal ikut terhapus karena ada di row yang sama)
            $application->delete();
            
            DB::commit();
            
            Log::info("✅ Application deleted", ['application_id' => $id]);
            
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