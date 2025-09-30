<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRegisterRequest;
use App\Http\Requests\InstitutionRegisterRequest;
use App\Models\User;
use App\Models\Student;
use App\Models\Institution;
use App\Mail\VerifyEmailMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    /**
     * tampilkan halaman pilihan jenis registrasi
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * tampilkan form registrasi mahasiswa
     */
    public function showStudentRegisterForm()
    {
        // TODO: ambil data universities dari database
        $universities = [];
        
        return view('auth.student-register', compact('universities'));
    }

    /**
     * tampilkan form registrasi instansi
     */
    public function showInstitutionRegisterForm()
    {
        // TODO: ambil data provinces dari database
        $provinces = [];
        $institutionTypes = [
            'pemerintah_desa' => 'pemerintah desa',
            'dinas' => 'dinas',
            'ngo' => 'NGO / lembaga non-profit',
            'puskesmas' => 'puskesmas',
            'sekolah' => 'sekolah',
            'perguruan_tinggi' => 'perguruan tinggi',
            'lainnya' => 'lainnya'
        ];
        
        return view('auth.institution-register', compact('provinces', 'institutionTypes'));
    }

    /**
     * proses registrasi mahasiswa
     */
    public function registerStudent(StudentRegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            // handle upload foto profil
            $profilePhotoPath = null;
            if ($request->hasFile('profile_photo')) {
                $profilePhotoPath = $request->file('profile_photo')->store('profiles', 'public');
            }

            // buat user
            $user = User::create([
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'user_type' => 'student',
                'is_active' => true,
                'email_verified_at' => null // akan diset setelah verifikasi
            ]);

            // buat profile student
            $student = Student::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'university_id' => $request->university_id,
                'major' => $request->major,
                'nim' => $request->nim,
                'semester' => $request->semester,
                'whatsapp_number' => $request->whatsapp_number,
                'profile_photo_url' => $profilePhotoPath,
                'portfolio_visible' => false,
                'show_email' => false,
                'show_phone' => false
            ]);

            // kirim email verifikasi
            Mail::to($user->email)->send(new VerifyEmailMail($user));

            DB::commit();

            // login otomatis setelah registrasi
            auth()->login($user, $request->filled('remember'));

            return redirect()
                ->route('verification.notice')
                ->with('success', 'registrasi berhasil! silakan cek email anda untuk verifikasi');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // hapus file yang sudah diupload jika ada error
            if (isset($profilePhotoPath)) {
                Storage::disk('public')->delete($profilePhotoPath);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'terjadi kesalahan saat registrasi. silakan coba lagi.']);
        }
    }

    /**
     * proses registrasi instansi
     */
    public function registerInstitution(InstitutionRegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            // handle upload logo
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            // handle upload dokumen verifikasi
            $documentPath = $request->file('verification_document')->store('documents', 'public');

            // buat user
            $user = User::create([
                'email' => $request->official_email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'user_type' => 'institution',
                'is_active' => true,
                'email_verified_at' => null // akan diset setelah verifikasi
            ]);

            // buat profile institution
            $institution = Institution::create([
                'user_id' => $user->id,
                'institution_name' => $request->institution_name,
                'institution_type' => $request->institution_type,
                'address' => $request->address,
                'province_id' => $request->province_id,
                'regency_id' => $request->regency_id,
                'pic_name' => $request->pic_name,
                'pic_position' => $request->pic_position,
                'phone_number' => $request->phone_number,
                'website' => $request->website,
                'description' => $request->description,
                'logo_url' => $logoPath,
                'verification_document_url' => $documentPath,
                'is_verified' => false, // menunggu verifikasi admin
                'verified_at' => null,
                'verified_by' => null
            ]);

            // kirim email verifikasi
            Mail::to($user->email)->send(new VerifyEmailMail($user));

            // TODO: kirim notifikasi ke admin untuk verifikasi instansi

            DB::commit();

            // login otomatis setelah registrasi
            auth()->login($user, $request->filled('remember'));

            return redirect()
                ->route('verification.notice')
                ->with('success', 'registrasi berhasil! silakan cek email anda untuk verifikasi. akun instansi anda juga akan diverifikasi oleh admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // hapus file yang sudah diupload jika ada error
            if (isset($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            if (isset($documentPath)) {
                Storage::disk('public')->delete($documentPath);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'terjadi kesalahan saat registrasi. silakan coba lagi.']);
        }
    }
}