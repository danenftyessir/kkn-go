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

        return view('auth.institution-register', compact('provinces', 'regencies'));
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
            
            // upload photo jika ada
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('students/photos', 'public');
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
                'whatsapp_number' => $request->whatsapp_number,
                'photo_path' => $photoPath,
            ]);
            
            DB::commit();
            
            // picu event bahwa user baru telah terdaftar (untuk kirim email verifikasi)
            event(new Registered($user));

            // log successful registration
            Log::info('Student registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'username' => $user->username
            ]);

            // login user secara otomatis
            Auth::login($user);

            // redirect ke dashboard dengan pesan sukses
            return redirect()
                ->route('student.dashboard')
                ->with('success', 'Selamat Datang di KKN-GO! Akun Anda berhasil dibuat. Jangan lupa verifikasi email Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student registration failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    /**
     * proses registrasi institution
     * PERBAIKAN: auto-login setelah registrasi berhasil seperti student registration
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
            
            // buat institution profile
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
                'is_verified' => false, // akan diverifikasi oleh admin
            ]);
            
            DB::commit();
            
            // picu event bahwa user baru telah terdaftar (untuk kirim email verifikasi)
            event(new Registered($user));

            // log successful registration
            Log::info('Institution registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
                'institution_name' => $request->institution_name
            ]);

            // PERBAIKAN: auto-login user setelah registrasi berhasil
            // ini memberikan feedback langsung bahwa data sudah tersimpan
            Auth::login($user);

            // redirect ke dashboard institusi dengan pesan sukses
            return redirect()
                ->route('institution.dashboard')
                ->with('success', 'Selamat Datang di KKN-GO! Akun Anda berhasil dibuat. Silakan lengkapi profil dan mulai posting masalah. Dokumen verifikasi Anda akan ditinjau oleh admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Institution registration failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }
}