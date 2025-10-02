<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * tampilkan halaman profil student
     */
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;
        
        // pastikan relasi student ada
        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'data profil tidak ditemukan');
        }
        
        return view('student.profile.index', compact('user', 'student'));
    }

    /**
     * tampilkan form edit profil
     */
    public function edit()
    {
        $user = auth()->user();
        $student = $user->student;
        
        // pastikan relasi student ada
        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'data profil tidak ditemukan');
        }
        
        // ambil data universities dari database
        $universities = University::orderBy('name', 'asc')->get();
        
        // TODO: jika ada table majors terpisah, ambil dari sana
        // untuk sementara major diinput manual sebagai text field
        $majors = [];
        
        return view('student.profile.edit', compact('user', 'student', 'universities', 'majors'));
    }

    /**
     * update profil student
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $student = $user->student;
        
        // pastikan relasi student ada
        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'data profil tidak ditemukan');
        }
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'university_id' => 'required|exists:universities,id',
            'major' => 'required|string|max:100',
            'semester' => 'required|integer|min:1|max:14',
            'phone' => 'required|string|regex:/^(\+62|62|0)[0-9]{9,12}$/',
            'bio' => 'nullable|string|max:500',
            'skills' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'portfolio_visible' => 'nullable|boolean'
        ]);

        // handle upload foto profil
        if ($request->hasFile('profile_photo')) {
            // hapus foto lama jika ada
            if ($student->profile_photo_path) {
                Storage::disk('public')->delete($student->profile_photo_path);
            }
            
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $validated['profile_photo_path'] = $path;
        }

        // update data student
        $student->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'university_id' => $validated['university_id'],
            'major' => $validated['major'],
            'semester' => $validated['semester'],
            'phone' => $validated['phone'],
            'bio' => $validated['bio'] ?? null,
            'profile_photo_path' => $validated['profile_photo_path'] ?? $student->profile_photo_path,
        ]);

        // TODO: update skills jika ada table terpisah
        // untuk sementara skills disimpan sebagai json atau text
        
        return redirect()->route('student.profile.index')
            ->with('success', 'profil berhasil diperbarui');
    }

    /**
     * tampilkan profil publik student berdasarkan username
     */
    public function publicProfile($username)
    {
        $user = User::where('username', $username)
            ->where('user_type', 'student')
            ->firstOrFail();
        
        $student = $user->student;
        
        // pastikan relasi student ada
        if (!$student) {
            abort(404, 'profil tidak ditemukan');
        }
        
        // TODO: cek apakah portfolio visible
        // jika tidak dan bukan owner, tampilkan 404
        // if (!$student->portfolio_visible && auth()->id() !== $user->id) {
        //     abort(404);
        // }
        
        // TODO: ambil data projects yang sudah completed
        $completedProjects = [];
        
        // TODO: ambil data statistics
        $stats = [
            'total_projects' => 0,
            'sdgs_addressed' => 0,
            'positive_reviews' => 0,
            'average_rating' => 0
        ];
        
        return view('student.profile.public', compact('user', 'student', 'completedProjects', 'stats'));
    }
}