<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRegisterRequest;
use App\Http\Requests\InstitutionRegisterRequest;
use App\Models\User;
use App\Models\Student;
use App\Models\Institution;
use App\Models\University;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;

/**
 * RegisterController
 * 
 * handle registrasi untuk student dan institution
 */
class RegisterController extends Controller
{
    /**
     * tampilkan halaman pilihan registrasi
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * tampilkan form registrasi student
     */
    public function showStudentRegisterForm()
    {
        // ambil data universities untuk dropdown
        $universities = University::orderBy('name', 'asc')->get();
        
        return view('auth.student-register', compact('universities'));
    }

    /**
     * tampilkan form registrasi institution
     */
    public function showInstitutionRegisterForm()
    {
        // ambil data provinces untuk dropdown
        $provinces = Province::orderBy('name', 'asc')->get();
        
        // siapkan regencies collection kosong untuk kondisi awal
        $regencies = collect();
        
        // jika ada old input province_id (ketika validasi gagal), load regencies-nya
        if (old('province_id')) {
            $regencies = Regency::where('province_id', old('province_id'))
                               ->orderBy('name', 'asc')
                               ->get();
        }

        // definisi tipe institusi
        $institutionTypes = [
            'pemerintah_desa' => 'pemerintah desa',
            'dinas' => 'dinas pemerintahan',
            'ngo' => 'NGO / lembaga swadaya masyarakat',
            'puskesmas' => 'puskesmas',
            'sekolah' => 'sekolah',
            'perguruan_tinggi' => 'perguruan tinggi',
            'lainnya' => 'lainnya'
        ];
        
        return view('auth.institution-register', compact('provinces', 'regencies', 'institutionTypes'));
    }

    /**
     * proses registrasi student
     */
    public function registerStudent(StudentRegisterRequest $request)
    {
        try {
            // gunakan database transaction untuk memastikan atomicity
            DB::beginTransaction();
            
            // buat user account
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'user_type' => 'student',
            ]);
            
            // upload foto profil jika ada
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('profiles/students', 'public');
            }
            
            // buat student profile
            $student = Student::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'university_id' => $request->university_id,
                'major' => $request->major,
                'nim' => $request->nim,
                'semester' => $request->semester,
                'phone' => $request->whatsapp_number,
                'profile_photo_path' => $photoPath,
            ]);
            
            DB::commit();
            
            // auto login setelah registrasi
            // Picu event bahwa user baru telah terdaftar
            event(new Registered($user));

            // Auto login setelah registrasi
            Auth::login($user);

            // Redirect ke halaman verifikasi email, bukan ke dashboard
            return redirect()->route('verification.notice');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student registration failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'terjadi kesalahan saat registrasi. silakan coba lagi.');
        }
    }

    /**
     * proses registrasi institution
     */
    public function registerInstitution(InstitutionRegisterRequest $request)
    {
        try {
            // gunakan database transaction untuk memastikan atomicity
            DB::beginTransaction();
            
            // buat user account
            $user = User::create([
                'name' => $request->institution_name,
                'email' => $request->official_email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'user_type' => 'institution',
            ]);
            
            // upload logo jika ada
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('institutions/logos', 'public');
            }
            
            // upload verification document
            $verificationDocPath = $request->file('verification_document')
                                          ->store('institutions/verifications', 'public');
            
            // buat institution profile
            $institution = Institution::create([
                'user_id' => $user->id,
                'institution_name' => $request->institution_name,
                'institution_type' => $request->institution_type,
                'address' => $request->address,
                'province_id' => $request->province_id,
                'regency_id' => $request->regency_id,
                'official_email' => $request->official_email,
                'phone_number' => $request->phone_number,
                'logo_path' => $logoPath,
                'pic_name' => $request->pic_name,
                'pic_position' => $request->pic_position,
                'verification_document_path' => $verificationDocPath,
                'website' => $request->website,
                'description' => $request->description,
                'is_verified' => false, // menunggu verifikasi admin
            ]);
            
            DB::commit();
            
            // auto login setelah registrasi
            // Picu event bahwa user baru telah terdaftar
            event(new Registered($user));

            // Auto login setelah registrasi
            Auth::login($user);

            // Redirect ke halaman verifikasi email, bukan ke dashboard
            return redirect()->route('verification.notice');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Institution registration failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'terjadi kesalahan saat registrasi. silakan coba lagi.');
        }
    }
}