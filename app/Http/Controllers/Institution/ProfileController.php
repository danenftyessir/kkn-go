<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Province;
use App\Models\Regency;

class ProfileController extends Controller
{
    /**
     * tampilkan halaman profil institution
     */
    public function index()
    {
        $institution = auth()->user()->institution;
        $user = auth()->user();
        
        return view('institution.profile.index', compact('institution', 'user'));
    }

    /**
     * tampilkan form edit profil
     */
    public function edit()
    {
        $institution = auth()->user()->institution;
        $user = auth()->user();
        $provinces = Province::orderBy('name')->get();
        $regencies = Regency::where('province_id', $institution->province_id)->orderBy('name')->get();
        
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
        
        return view('institution.profile.edit', compact('institution', 'user', 'provinces', 'regencies', 'institutionTypes'));
    }

    /**
     * update profil institution
     */
    public function update(Request $request)
    {
        $institution = auth()->user()->institution;
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'website' => 'nullable|url',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pic_name' => 'required|string|max:100',
            'pic_position' => 'required|string|max:100',
        ]);

        // handle logo upload
        if ($request->hasFile('logo')) {
            // hapus logo lama jika ada
            if ($institution->logo_path) {
                Storage::disk('public')->delete($institution->logo_path);
            }
            
            // simpan logo baru
            $logoPath = $request->file('logo')->store('institutions/logos', 'public');
            $validated['logo_path'] = $logoPath;
        }

        // update institution
        $institution->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'province_id' => $validated['province_id'],
            'regency_id' => $validated['regency_id'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'website' => $validated['website'] ?? null,
            'description' => $validated['description'] ?? null,
            'pic_name' => $validated['pic_name'],
            'pic_position' => $validated['pic_position'],
            'logo_path' => $validated['logo_path'] ?? $institution->logo_path,
        ]);

        // update user email jika berubah
        if ($user->email !== $validated['email']) {
            $user->update(['email' => $validated['email']]);
        }

        return redirect()->route('institution.profile.index')
                        ->with('success', 'profil berhasil diperbarui!');
    }

    /**
     * update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // cek password lama
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'password lama tidak sesuai']);
        }

        // update password
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'password berhasil diperbarui!');
    }

    /**
     * tampilkan profil publik institution
     */
    public function public($slug)
    {
        // untuk saat ini gunakan id, nanti bisa diubah ke slug
        $institution = Institution::with(['user', 'province', 'regency', 'problems' => function($q) {
            $q->where('status', 'open')->orWhere('status', 'completed');
        }])->findOrFail($slug);
        
        return view('institution.profile.public', compact('institution'));
    }
}