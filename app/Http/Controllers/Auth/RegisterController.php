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
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * alias untuk showRegistrationForm
     */
    public function showRegisterForm()
    {
        return $this->showRegistrationForm();
    }

    /**
     * tampilkan form registrasi student
     */
    public function showStudentForm()
    {
        // ambil data universities untuk dropdown
        $universities = University::orderBy('name', 'asc')->get();
        
        return view('auth.student-register', compact('universities'));
    }

    /**
     * alias untuk showStudentForm
     */
    public function showStudentRegisterForm()
    {
        return $this->showStudentForm();
    }

    /**
     * tampilkan form registrasi institution
     */
    public function showInstitutionForm()
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
     * alias untuk showInstitutionForm
     */
    public function showInstitutionRegisterForm()
    {
        return $this->showInstitutionForm();
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
                'is_active' => true,
            ]);
            
            // upload foto profil jika ada
            $photoPath = null;
            if ($request->hasFile('profile_photo')) {
                $photoPath = $request->file('profile_photo')->store('profiles/students', 'public');
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
            
            // picu event bahwa user baru telah terdaftar
            event(new Registered($user));

            // auto login setelah registrasi
            Auth::login($user);

            // redirect ke halaman verifikasi email
            return redirect()->route('verification.notice');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student registration failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'terjadi kesalahan saat registrasi. silakan coba lagi. Error: ' . $e->getMessage());
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
                'is_active' => true,
            ]);
            
            // upload logo jika ada
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('institutions/logos', 'public');
            }
            
            // upload verification document
            $verificationDocPath = null;
            if ($request->hasFile('verification_document')) {
                $verificationDocPath = $request->file('verification_document')
                                              ->store('institutions/verifications', 'public');
            }
            
            // buat institution profile - PERBAIKAN: gunakan nama kolom yang sesuai dengan migration
            $institution = Institution::create([
                'user_id' => $user->id,
                'name' => $request->institution_name,
                'type' => $request->institution_type,
                'address' => $request->address,
                'province_id' => $request->province_id,
                'regency_id' => $request->regency_id,
                'email' => $request->official_email,
                'phone' => $request->phone_number,
                'logo_path' => $logoPath,
                'pic_name' => $request->pic_name,
                'pic_position' => $request->pic_position,
                'verification_document_path' => $verificationDocPath,
                'website' => $request->website,
                'description' => $request->description,
                'is_verified' => false,
            ]);
            
            DB::commit();
            
            // picu event bahwa user baru telah terdaftar
            event(new Registered($user));

            // auto login setelah registrasi
            Auth::login($user);

            // redirect ke halaman verifikasi email
            return redirect()->route('verification.notice');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Institution registration failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'terjadi kesalahan saat registrasi. silakan coba lagi. Error: ' . $e->getMessage());
        }
    }
}