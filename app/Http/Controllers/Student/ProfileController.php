<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Http\Requests\UpdateStudentProfileRequest;
use App\Http\Requests\UpdateStudentPasswordRequest;

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
    public function update(UpdateStudentProfileRequest $request)
    {
        // Dapatkan data yang sudah tervalidasi dan ternormalisasi dari FormRequest
        $validated = $request->validated();
        
        $student = auth()->user()->student;

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
        
        // Dapatkan password baru yang sudah tervalidasi
        $validated = $request->validated();

        // update password
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
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

    /**
     * normalize nomor telepon
     */
    private function normalizePhoneNumber(string $phone): string
    {
        // hapus spasi dan karakter non-numeric kecuali +
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // convert 08xx menjadi 628xx
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }
        
        // tambahkan + jika belum ada dan dimulai dengan 62
        if (!str_starts_with($phone, '+') && str_starts_with($phone, '62')) {
            $phone = '+' . $phone;
        }
        
        return $phone;
    }
}