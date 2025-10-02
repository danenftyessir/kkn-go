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
            'phone' => ['required', 'string', 'regex:/^(\+62|62|0)[0-9]{9,12}$/'],
            'bio' => 'nullable|string|max:500',
            'skills' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'portfolio_visible' => 'nullable|boolean'
        ], [
            // pesan error kustom
            'first_name.required' => 'nama depan wajib diisi',
            'last_name.required' => 'nama belakang wajib diisi',
            'university_id.required' => 'universitas wajib dipilih',
            'university_id.exists' => 'universitas tidak valid',
            'major.required' => 'jurusan wajib diisi',
            'semester.required' => 'semester wajib diisi',
            'semester.min' => 'semester minimal 1',
            'semester.max' => 'semester maksimal 14',
            'phone.required' => 'nomor whatsapp wajib diisi',
            'phone.regex' => 'format nomor whatsapp tidak valid. gunakan format: 08xxxxxxxxx',
            'bio.max' => 'bio maksimal 500 karakter',
            'profile_photo.image' => 'file harus berupa gambar',
            'profile_photo.mimes' => 'foto profil harus berformat jpeg, jpg, atau png',
            'profile_photo.max' => 'ukuran foto profil maksimal 2MB'
        ]);

        // normalize nomor telepon
        $validated['phone'] = $this->normalizePhoneNumber($validated['phone']);

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
     * update password student
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'password saat ini wajib diisi',
            'password.required' => 'password baru wajib diisi',
            'password.confirmed' => 'konfirmasi password tidak cocok',
            'password.min' => 'password minimal 8 karakter'
        ]);

        // verifikasi password saat ini
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'password saat ini salah']);
        }

        // update password
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'password berhasil diperbarui');
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