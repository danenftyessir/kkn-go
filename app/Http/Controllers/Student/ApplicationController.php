<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Problem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    /**
     * tampilkan daftar aplikasi mahasiswa
     */
    public function index()
    {
        $student = Auth::user()->student;
        
        $applications = Application::with(['problem.institution', 'problem.province', 'problem.regency'])
                                  ->where('student_id', $student->id)
                                  ->orderBy('applied_at', 'desc')
                                  ->paginate(10);
        
        // hitung statistik
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
                ->route('student.browse-problems.index')
                ->with('error', 'Maaf, proyek ini sudah tidak menerima aplikasi');
        }
        
        // validasi deadline belum lewat
        if ($problem->application_deadline < now()) {
            return redirect()
                ->route('student.browse-problems.show', $problem->id)
                ->with('error', 'Maaf, deadline aplikasi sudah berakhir');
        }
        
        // cek apakah sudah pernah apply
        $student = Auth::user()->student;
        $hasApplied = Application::where('student_id', $student->id)
                                ->where('problem_id', $problem->id)
                                ->exists();
        
        if ($hasApplied) {
            return redirect()
                ->route('student.browse-problems.show', $problem->id)
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
        
        $validated = $request->validate([
            'problem_id' => 'required|exists:problems,id',
            'motivation' => 'required|string|min:100',
            'relevant_experience' => 'nullable|string',
            'proposal' => 'required|file|mimes:pdf|max:5120', // max 5MB
        ], [
            'motivation.min' => 'Motivasi minimal 100 karakter',
            'proposal.required' => 'Proposal wajib diunggah',
            'proposal.mimes' => 'Proposal harus berformat PDF',
            'proposal.max' => 'Ukuran proposal maksimal 5MB',
        ]);
        
        $problem = Problem::findOrFail($validated['problem_id']);
        
        // validasi ulang
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
            
            // upload proposal
            $proposalPath = $request->file('proposal')->store('proposals', 'public');
            
            // simpan aplikasi
            $application = Application::create([
                'student_id' => $student->id,
                'problem_id' => $problem->id,
                'motivation' => $validated['motivation'],
                'relevant_experience' => $validated['relevant_experience'],
                'proposal_path' => $proposalPath,
                'status' => 'pending',
                'applied_at' => now(),
            ]);
            
            // increment counter di problem
            $problem->increment('applications_count');
            
            // TODO: kirim notifikasi ke instansi
            
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

    /**
     * hapus aplikasi (alias untuk withdraw untuk backward compatibility)
     */
    public function destroy($id)
    {
        return $this->withdraw($id);
    }
}