<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Problem;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * ApplicationController
 * 
 * handle aplikasi mahasiswa ke problems/proyek
 */
class ApplicationController extends Controller
{
    /**
     * tampilkan halaman my applications
     */
    public function index(Request $request)
    {
        $student = Auth::user()->student;
        
        // ambil semua aplikasi mahasiswa dengan relasi
        $query = Application::with(['problem.institution', 'problem.province', 'problem.regency'])
                           ->where('student_id', $student->id);
        
        // filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->latest('applied_at');
                break;
            case 'oldest':
                $query->oldest('applied_at');
                break;
            case 'status':
                $query->orderByRaw("FIELD(status, 'accepted', 'reviewed', 'pending', 'rejected')");
                break;
        }
        
        $applications = $query->paginate(10)->withQueryString();
        
        // statistik untuk dashboard
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
     * tampilkan detail aplikasi
     */
    public function show($id)
    {
        $student = Auth::user()->student;
        
        $application = Application::with(['problem.institution', 'problem.province', 'problem.regency'])
                                 ->where('student_id', $student->id)
                                 ->findOrFail($id);
        
        return view('student.applications.show', compact('application'));
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
                ->route('student.browse-problems')
                ->with('error', 'Maaf, proyek ini sudah tidak menerima aplikasi');
        }
        
        // validasi deadline belum lewat
        if ($problem->application_deadline < now()) {
            return redirect()
                ->route('student.problems.show', $problem->id)
                ->with('error', 'Maaf, deadline aplikasi sudah berakhir');
        }
        
        // cek apakah sudah pernah apply
        $student = Auth::user()->student;
        $hasApplied = Application::where('student_id', $student->id)
                                ->where('problem_id', $problem->id)
                                ->exists();
        
        if ($hasApplied) {
            return redirect()
                ->route('student.problems.show', $problem->id)
                ->with('info', 'Anda sudah mengajukan aplikasi untuk proyek ini');
        }
        
        return view('student.applications.create', compact('problem'));
    }
    
    /**
     * submit aplikasi baru
     */
    public function store(Request $request, $problemId)
    {
        $problem = Problem::findOrFail($problemId);
        $student = Auth::user()->student;
        
        // validasi input
        $validated = $request->validate([
            'motivation' => 'required|string|min:100|max:2000',
            'cover_letter' => 'nullable|string|max:2000',
            'proposal' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // max 5MB
        ], [
            'motivation.required' => 'Motivasi wajib diisi',
            'motivation.min' => 'Motivasi minimal 100 karakter',
            'motivation.max' => 'Motivasi maksimal 2000 karakter',
            'proposal.mimes' => 'Proposal harus berformat PDF, DOC, atau DOCX',
            'proposal.max' => 'Ukuran proposal maksimal 5MB',
        ]);
        
        // cek apakah sudah pernah apply
        $hasApplied = Application::where('student_id', $student->id)
                                ->where('problem_id', $problem->id)
                                ->exists();
        
        if ($hasApplied) {
            return back()->with('error', 'Anda sudah mengajukan aplikasi untuk proyek ini');
        }
        
        try {
            DB::beginTransaction();
            
            // upload proposal jika ada
            $proposalPath = null;
            if ($request->hasFile('proposal')) {
                $proposalPath = $request->file('proposal')->store('proposals', 'public');
            }
            
            // buat aplikasi baru
            $application = Application::create([
                'student_id' => $student->id,
                'problem_id' => $problem->id,
                'motivation' => $validated['motivation'],
                'cover_letter' => $validated['cover_letter'] ?? null,
                'proposal_path' => $proposalPath,
                'status' => 'pending',
                'applied_at' => now(),
            ]);
            
            // increment counter di problem
            $problem->increment('applications_count');
            
            // TODO: kirim notifikasi ke instansi
            // TODO: kirim email konfirmasi ke mahasiswa
            
            DB::commit();
            
            return redirect()
                ->route('student.applications.show', $application->id)
                ->with('success', 'Aplikasi berhasil dikirim! Instansi akan meninjau aplikasi Anda.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // hapus file proposal jika ada error
            if (isset($proposalPath)) {
                Storage::disk('public')->delete($proposalPath);
            }
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengirim aplikasi. Silakan coba lagi.');
        }
    }
    
    /**
     * withdraw/batalkan aplikasi
     */
    public function withdraw($id)
    {
        $student = Auth::user()->student;
        
        $application = Application::where('student_id', $student->id)
                                 ->where('id', $id)
                                 ->firstOrFail();
        
        // hanya bisa withdraw jika status masih pending atau reviewed
        if (!in_array($application->status, ['pending', 'reviewed'])) {
            return back()->with('error', 'Aplikasi tidak dapat dibatalkan');
        }
        
        try {
            DB::beginTransaction();
            
            // hapus proposal file jika ada
            if ($application->proposal_path) {
                Storage::disk('public')->delete($application->proposal_path);
            }
            
            // hapus aplikasi
            $application->delete();
            
            // decrement counter di problem
            $application->problem->decrement('applications_count');
            
            // TODO: kirim notifikasi ke instansi
            
            DB::commit();
            
            return redirect()
                ->route('student.applications.index')
                ->with('success', 'Aplikasi berhasil dibatalkan');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan saat membatalkan aplikasi');
        }
    }
}