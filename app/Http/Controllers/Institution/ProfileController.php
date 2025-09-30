<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * tampilkan halaman profil institution
     */
    public function index()
    {
        $user = auth()->user();
        $institution = $user->institution;
        
        return view('institution.profile.index', compact('user', 'institution'));
    }

    /**
     * tampilkan form edit profil
     */
    public function edit()
    {
        $user = auth()->user();
        $institution = $user->institution;
        
        // TODO: ambil data provinces dan regencies dari database
        $provinces = [];
        $regencies = [];
        
        return view('institution.profile.edit', compact('user', 'institution', 'provinces', 'regencies'));
    }

    /**
     * update profil institution
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $institution = $user->institution;
        
        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'institution_type' => 'required|in:pemerintah_desa,dinas,ngo,puskesmas,sekolah,perguruan_tinggi,lainnya',
            'address' => 'required|string|max:500',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'pic_name' => 'required|string|max:100',
            'pic_position' => 'required|string|max:100',
            'phone_number' => 'required|string|regex:/^(\+62|62|0)[0-9]{9,12}$/',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        // handle upload logo
        if ($request->hasFile('logo')) {
            // hapus logo lama jika ada
            if ($institution->logo_url) {
                Storage::disk('public')->delete($institution->logo_url);
            }
            
            $path = $request->file('logo')->store('logos', 'public');
            $institution->logo_url = $path;
        }

        // update data institution
        $institution->update([
            'institution_name' => $validated['institution_name'],
            'institution_type' => $validated['institution_type'],
            'address' => $validated['address'],
            'province_id' => $validated['province_id'],
            'regency_id' => $validated['regency_id'],
            'pic_name' => $validated['pic_name'],
            'pic_position' => $validated['pic_position'],
            'phone_number' => $validated['phone_number'],
            'website' => $validated['website'] ?? null,
            'description' => $validated['description'] ?? null,
            'logo_url' => $institution->logo_url
        ]);

        return redirect()
            ->route('institution.profile.index')
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
     * tampilkan profil publik institution
     */
    public function show($username)
    {
        // TODO: cari user berdasarkan username dan tampilkan profil publik
        $user = \App\Models\User::where('username', $username)
            ->where('user_type', 'institution')
            ->firstOrFail();
        
        $institution = $user->institution;
        
        // TODO: ambil data problems, projects, dan reviews
        $problems = [];
        $activeProjects = [];
        $completedProjects = [];
        $reviews = [];
        
        return view('institution.profile.public', compact('user', 'institution', 'problems', 'activeProjects', 'completedProjects', 'reviews'));
    }

    /**
     * hapus logo
     */
    public function deleteLogo()
    {
        $institution = auth()->user()->institution;
        
        if ($institution->logo_url) {
            Storage::disk('public')->delete($institution->logo_url);
            $institution->update(['logo_url' => null]);
        }
        
        return back()->with('success', 'logo berhasil dihapus');
    }

    /**
     * upload ulang dokumen verifikasi
     */
    public function uploadVerificationDocument(Request $request)
    {
        $request->validate([
            'verification_document' => 'required|file|mimes:pdf|max:5120'
        ], [
            'verification_document.required' => 'dokumen verifikasi wajib diunggah',
            'verification_document.mimes' => 'dokumen harus berformat PDF',
            'verification_document.max' => 'ukuran dokumen maksimal 5MB'
        ]);

        $institution = auth()->user()->institution;
        
        // hapus dokumen lama
        if ($institution->verification_document_url) {
            Storage::disk('public')->delete($institution->verification_document_url);
        }
        
        // simpan dokumen baru
        $path = $request->file('verification_document')->store('documents', 'public');
        
        $institution->update([
            'verification_document_url' => $path,
            'is_verified' => false, // reset status verifikasi
            'verified_at' => null,
            'verified_by' => null
        ]);

        return back()->with('success', 'dokumen verifikasi berhasil diunggah. menunggu verifikasi admin');
    }
}