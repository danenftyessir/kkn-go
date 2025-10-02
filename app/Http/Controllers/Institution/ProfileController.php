<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * controller untuk profile instansi
 */
class ProfileController extends Controller
{
    /**
     * tampilkan profile instansi (private - untuk owner)
     */
    public function index()
    {
        $institution = auth()->user()->institution;
        
        // TODO: implementasi view profile lengkap
        return view('institution.profile.index', compact('institution'));
    }

    /**
     * tampilkan form edit profile
     */
    public function edit()
    {
        $institution = auth()->user()->institution;
        
        // TODO: implementasi form edit profile
        return view('institution.profile.edit', compact('institution'));
    }

    /**
     * update profile instansi
     */
    public function update(Request $request)
    {
        // TODO: implementasi update profile
        return redirect()
            ->route('institution.profile.index')
            ->with('success', 'profile berhasil diupdate');
    }

    /**
     * tampilkan profile publik instansi
     */
    public function publicProfile($username)
    {
        // TODO: implementasi public profile
        // cari institution berdasarkan username
        return view('institution.profile.public');
    }
}