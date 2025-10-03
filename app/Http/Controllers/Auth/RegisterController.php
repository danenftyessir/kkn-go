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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        
        $regencies = collect();
        if (old('province_id')) {
            $regencies = Regency::where('province_id', old('province_id'))->orderBy('name', 'asc')->get();
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
                'is_active' => true,
                // email_verified_at untuk sementara di-set null, nanti verifikasi lewat email
                'email_verified_at' => null,
            ]);

            // buat data student
            $student = Student::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'university_id' => $request->university_id,
                'major' => $request->major,
                'nim' => $request->nim,
                'semester' => $request->semester,
                'phone' => $request->whatsapp_number,
                'profile_photo_path' => null,
                'bio' => null,
            ]);

            // commit transaction
            DB::commit();

            // log successful registration
            Log::info('student registered successfully', [
                'user_id' => $user->id,
                'student_id' => $student->id,
                'email' => $user->email
            ]);

            // TODO: kirim email verifikasi
            // dispatch(new SendEmailVerificationNotification($user));

            // login otomatis setelah registrasi
            Auth::login($user);

            // redirect ke dashboard student dengan success message
            return redirect()->route('student.dashboard')
                ->with('success', 'registrasi berhasil! selamat datang di KKN-GO.');

        } catch (\Exception $e) {
            // rollback jika ada error
            DB::rollBack();
            
            // log error
            Log::error('student registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // redirect kembali dengan error message
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'terjadi kesalahan saat registrasi. silakan coba lagi.');
        }
    }

    /**
     * proses registrasi institution
     */
    public function registerInstitution(InstitutionRegisterRequest $request)
    {
        try {
            // gunakan database transaction
            DB::beginTransaction();

            // buat user account
            $user = User::create([
                'name' => $request->institution_name,
                'email' => $request->official_email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'user_type' => 'institution',
                'is_active' => true,
                'email_verified_at' => null,
            ]);

            // handle upload logo jika ada
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            // handle upload dokumen verifikasi jika ada
            $verificationDocPath = null;
            if ($request->hasFile('verification_document')) {
                $verificationDocPath = $request->file('verification_document')->store('verifications', 'public');
            }

            // buat data institution
            $institution = Institution::create([
                'user_id' => $user->id,
                'name' => $request->institution_name,
                'type' => $request->institution_type,
                'address' => $request->address,
                'province_id' => $request->province_id,
                'regency_id' => $request->regency_id,
                'email' => $request->official_email,
                'phone' => $request->phone,
                'logo_path' => $logoPath,
                'pic_name' => $request->pic_name,
                'pic_position' => $request->pic_position,
                'verification_document_path' => $verificationDocPath,
                'is_verified' => false, // default belum terverifikasi, perlu approval admin
                'verified_at' => null,
                'description' => null,
            ]);

            // commit transaction
            DB::commit();

            // log successful registration
            Log::info('institution registered successfully', [
                'user_id' => $user->id,
                'institution_id' => $institution->id,
                'email' => $user->email
            ]);

            // TODO: kirim email verifikasi
            // TODO: kirim notifikasi ke admin untuk approval

            // login otomatis setelah registrasi
            Auth::login($user);

            // redirect ke dashboard institution dengan info message
            return redirect()->route('institution.dashboard')
                ->with('info', 'registrasi berhasil! akun anda akan diverifikasi oleh admin dalam 1-3 hari kerja.');

        } catch (\Exception $e) {
            // rollback jika ada error
            DB::rollBack();
            
            // log error
            Log::error('institution registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // redirect kembali dengan error message
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'terjadi kesalahan saat registrasi. silakan coba lagi.');
        }
    }
}