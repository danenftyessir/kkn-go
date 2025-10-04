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
        
        return view('institution.profile.edit', compact('institution', 'user', 'provinces', 'regencies'));
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
        ]);

        // handle logo upload
        if ($request->hasFile('logo')) {
            // hapus logo lama jika ada
            if ($institution->logo_path) {
                Storage::disk('public')->delete($institution->logo_path);
            }
            
            // simpan logo baru
            $path = $request->file('logo')->store('institutions/logos', 'public');
            $validated['logo_path'] = $path;
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
            'logo_path' => $validated['logo_path'] ?? $institution->logo_path,
        ]);

        // update user email jika berubah
        if ($user->email !== $validated['email']) {
            $user->update(['email' => $validated['email']]);
        }

        return redirect()->route('institution.profile.index')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * update password institution
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // cek current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        // update password
        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return redirect()->route('institution.profile.index')
            ->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * tampilkan public profile institution
     */
    public function publicProfile($id)
    {
        $institution = \App\Models\Institution::with([
            'user',
            'province',
            'regency',
            'problems' => function($q) {
                $q->where('status', 'open')->latest()->limit(5);
            }
        ])->findOrFail($id);

        // statistik
        $stats = [
            'total_problems' => $institution->problems()->count(),
            'active_problems' => $institution->problems()->where('status', 'open')->count(),
            'completed_projects' => \App\Models\Project::whereHas('problem', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })->where('status', 'completed')->count(),
        ];

        return view('institution.profile.public', compact('institution', 'stats'));
    }
}