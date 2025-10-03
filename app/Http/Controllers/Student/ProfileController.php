<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentPasswordRequest;
use App\Http\Requests\UpdateStudentProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * tampilkan halaman profil student
     */
    public function index()
    {
        $student = auth()->user()->student;
        $user = auth()->user();
        return view('student.profile.index', compact('student', 'user'));
    }

    /**
     * tampilkan form edit profil
     */
    public function edit()
    {
        $student = auth()->user()->student;
        $user = auth()->user();
        return view('student.profile.edit', compact('student', 'user'));
    }

    /**
     * update profil student
     */
    public function update(UpdateStudentProfileRequest $request)
    {
        $student = auth()->user()->student;
        $validated = $request->validated();
        
        // handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // hapus foto lama jika ada
            if ($student->profile_photo_path) {
                Storage::disk('public')->delete($student->profile_photo_path);
            }
            
            // simpan foto baru
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $validated['profile_photo_path'] = $path;
        }
        
        // hapus field profile_photo dari validated karena tidak ada di database
        unset($validated['profile_photo']);
        
        // update data student
        $student->update($validated);
        
        return redirect()->route('student.profile.index')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * update password student
     */
    public function updatePassword(UpdateStudentPasswordRequest $request)
    {
        $user = auth()->user();
        
        // update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return redirect()->route('student.profile.index')
            ->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * tampilkan public profile student
     */
    public function publicProfile($username)
    {
        // cari student berdasarkan username dari user
        $student = \App\Models\Student::whereHas('user', function($query) use ($username) {
            $query->where('username', $username);
        })
        ->with(['user', 'university'])
        ->firstOrFail();
        
        // ambil user dari relasi student
        $user = $student->user;
        
        // TODO: load completed projects dari database
        $completedProjects = [];
        
        // TODO: load achievements
        $achievements = [];
        
        // hitung statistik untuk portfolio
        $stats = [
            'total_projects' => 0, // TODO: hitung dari completed projects
            'sdgs_addressed' => 0, // TODO: hitung unique SDGs dari projects
            'positive_reviews' => 0, // TODO: hitung dari reviews
            'average_rating' => 0.0, // TODO: hitung average rating dari reviews
            'impact_metrics' => 0, // TODO: hitung impact metrics
        ];
        
        return view('student.profile.public', compact('student', 'user', 'stats', 'completedProjects', 'achievements'));
    }
}