<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * tampilkan halaman profil student
     */
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;
        
        return view('student.profile.index', compact('user', 'student'));
    }

    /**
     * tampilkan form edit profil
     */
    public function edit()
    {
        $user = auth()->user();
        $student = $user->student;
        
        // TODO: ambil data universities dan majors dari database
        $universities = [];
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
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'university_id' => 'required|exists:universities,id',
            'major' => 'required|string|max:100',
            'semester' => 'required|integer|min:1|max:14',
            'whatsapp_number' => 'required|string|regex:/^(\+62|62|0)[0-9]{9,12}$/',
            'bio' => 'nullable|string|max:500',
            'skills' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        // handle upload foto profil
        if ($request->hasFile('profile_photo')) {
            // hapus foto lama jika ada
            if ($student->profile_photo_url) {
                Storage::disk('public')->delete($student->profile_photo_url);
            }
            
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $student->profile_photo_url = $path;
        }

        // update data student
        $student->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'university_id' => $validated['university_id'],
            'major' => $validated['major'],
            'semester' => $validated['semester'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'bio' => $validated['bio'] ?? null,
            'profile_photo_url' => $student->profile_photo_url
        ]);

        // handle skills (convert dari string ke array)
        if (isset($validated['skills'])) {
            $skills = array_map('trim', explode(',', $validated['skills']));
            // TODO: simpan skills ke tabel pivot student_skill
        }

        return redirect()
            ->route('student.profile.index')
            ->with('success', 'profil berhasil diperbarui');
    }

    /**
     * update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ]
        ], [
            'current_password.required' => 'password saat ini wajib diisi',
            'password.required' => 'password baru wajib diisi',
            'password.confirmed' => 'konfirmasi password tidak cocok'
        ]);

        $user = auth()->user();

        // cek password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'password saat ini tidak sesuai'
            ]);
        }

        // update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'password berhasil diperbarui');
    }

    /**
     * update pengaturan privasi
     */
    public function updatePrivacy(Request $request)
    {
        $validated = $request->validate([
            'portfolio_visible' => 'required|boolean',
            'show_email' => 'required|boolean',
            'show_phone' => 'required|boolean'
        ]);

        $student = auth()->user()->student;
        
        $student->update([
            'portfolio_visible' => $validated['portfolio_visible'],
            'show_email' => $validated['show_email'],
            'show_phone' => $validated['show_phone']
        ]);

        return back()->with('success', 'pengaturan privasi berhasil diperbarui');
    }

    /**
     * tampilkan profil publik student
     */
    public function show($username)
    {
        // TODO: cari user berdasarkan username dan tampilkan profil publik
        $user = \App\Models\User::where('username', $username)
            ->where('user_type', 'student')
            ->firstOrFail();
        
        $student = $user->student;
        
        // cek apakah portfolio visible
        if (!$student->portfolio_visible) {
            abort(403, 'profil ini bersifat privat');
        }
        
        // TODO: ambil data projects, skills, dan achievements
        $projects = [];
        $skills = [];
        $achievements = [];
        
        return view('student.profile.public', compact('user', 'student', 'projects', 'skills', 'achievements'));
    }

    /**
     * hapus foto profil
     */
    public function deletePhoto()
    {
        $student = auth()->user()->student;
        
        if ($student->profile_photo_url) {
            Storage::disk('public')->delete($student->profile_photo_url);
            $student->update(['profile_photo_url' => null]);
        }
        
        return back()->with('success', 'foto profil berhasil dihapus');
    }
}