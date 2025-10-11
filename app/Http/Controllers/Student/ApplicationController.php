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
        Log::info("=== START Application Store ===", [
            'user_id' => auth()->id(),
            'request_data' => $request->except(['proposal']),
        ]);
        
        $student = Auth::user()->student;
        
        if (!$student) {
            Log::error("Student not found for user", ['user_id' => auth()->id()]);
            return back()->with('error', 'Data mahasiswa tidak ditemukan');
        }
        
        // validasi input
        try {
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
            
            Log::info("✅ Validation passed");
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("❌ Validation failed", ['errors' => $e->errors()]);
            throw $e;
        }
        
        $problem = Problem::findOrFail($validated['problem_id']);
        
        Log::info("Problem found", [
            'problem_id' => $problem->id,
            'title' => $problem->title,
            'status' => $problem->status,
        ]);
        
        // validasi problem masih open dan deadline belum lewat
        if ($problem->status !== 'open' || $problem->application_deadline < now()) {
            Log::warning("Problem not accepting applications", [
                'problem_id' => $problem->id,
                'status' => $problem->status,
                'deadline' => $problem->application_deadline,
            ]);
            return back()->with('error', 'Proyek sudah tidak menerima aplikasi');
        }
        
        // cek apakah sudah pernah apply
        $hasApplied = Application::where('student_id', $student->id)
                                ->where('problem_id', $problem->id)
                                ->exists();
        
        if ($hasApplied) {
            Log::warning("Student already applied", [
                'student_id' => $student->id,
                'problem_id' => $problem->id,
            ]);
            return back()->with('error', 'Anda sudah mengajukan aplikasi untuk proyek ini');
        }
        
        try {
            DB::beginTransaction();
            Log::info("Transaction started");
            
            // data dasar aplikasi
            $applicationData = [
                'student_id' => $student->id,
                'problem_id' => $problem->id,
                'motivation' => $validated['motivation'],
                'status' => 'pending',
                'applied_at' => now(),
            ];
            
            Log::info("Base application data prepared", $applicationData);
            
            // simpan file proposal ke database jika ada
            if ($request->hasFile('proposal')) {
                $file = $request->file('proposal');
                
                Log::info("📄 Processing proposal file", [
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]);
                
                try {
                    // baca file content sebagai base64
                    $fileContent = base64_encode(file_get_contents($file->getRealPath()));
                    
                    $applicationData['proposal_content'] = $fileContent;
                    $applicationData['proposal_filename'] = $file->getClientOriginalName();
                    $applicationData['proposal_mime_type'] = $file->getMimeType();
                    $applicationData['proposal_size'] = $file->getSize();
                    
                    Log::info("✅ File encoded successfully", [
                        'filename' => $file->getClientOriginalName(),
                        'encoded_size' => strlen($fileContent),
                    ]);
                    
                } catch (\Exception $e) {
                    Log::error("❌ Failed to encode file", [
                        'error' => $e->getMessage(),
                    ]);
                    throw new \Exception('Gagal memproses file proposal: ' . $e->getMessage());
                }
            } else {
                Log::info("No proposal file uploaded");
            }
            
            // simpan aplikasi
            try {
                Log::info("Creating application record...");
                $application = Application::create($applicationData);
                Log::info("✅ Application created successfully", [
                    'application_id' => $application->id,
                ]);
                
            } catch (\Exception $e) {
                Log::error("❌ Failed to create application", [
                    'error' => $e->getMessage(),
                    'sql_error' => $e->getMessage(),
                ]);
                throw new \Exception('Gagal menyimpan aplikasi ke database: ' . $e->getMessage());
            }
            
            // increment counter aplikasi di problem
            try {
                $problem->increment('applications_count');
                Log::info("✅ Problem counter incremented");
            } catch (\Exception $e) {
                Log::error("⚠️ Failed to increment counter (non-critical)", [
                    'error' => $e->getMessage(),
                ]);
            }
            
            // kirim notifikasi ke instansi
            try {
                Log::info("Sending notification to institution...", [
                    'institution_id' => $problem->institution_id,
                ]);
                
                // load relasi yang dibutuhkan
                $application->load(['student.user', 'problem.institution']);
                
                $this->notificationService->applicationSubmitted($application);
                
                Log::info("✅ Notification sent successfully");
                
            } catch (\Exception $e) {
                Log::error("⚠️ Failed to send notification (non-critical)", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                // tidak perlu rollback untuk error notifikasi
            }
            
            DB::commit();
            Log::info("✅ Transaction committed successfully");
            Log::info("=== END Application Store (SUCCESS) ===");
            
            return redirect()
                ->route('student.applications.show', $application->id)
                ->with('success', 'Aplikasi berhasil dikirim! Instansi akan meninjau aplikasi Anda.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("❌ Transaction rolled back", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            Log::error("=== END Application Store (FAILED) ===");
            
            // return error message yang jelas
            $errorMessage = 'Terjadi kesalahan saat mengirim aplikasi: ' . $e->getMessage();
            
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
        
        Log::info("Downloading proposal", [
            'application_id' => $id,
            'filename' => $application->proposal_filename,
        ]);
        
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
            
            Log::error("❌ Error saat membatalkan aplikasi", [
                'application_id' => $id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat membatalkan aplikasi.');
        }
    }
}