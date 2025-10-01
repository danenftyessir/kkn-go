<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| bypass login routes (untuk testing saja!)
|--------------------------------------------------------------------------
| routes ini untuk bypass authentication saat testing
| HAPUS di production!
*/

// halaman pilih role untuk bypass login
Route::get('/dev-login', function () {
    return view('dev.login');
})->name('dev.login');

// bypass login sebagai mahasiswa
Route::get('/dev-login/student', function () {
    session([
        'user' => [
            'id' => 1,
            'email' => 'budi.santoso@mail.ugm.ac.id',
            'username' => 'budisantoso',
            'user_type' => 'student',
            'is_active' => true,
            'profile' => [
                'first_name' => 'Budi',
                'last_name' => 'Santoso',
                'university' => 'Universitas Gadjah Mada',
                'major' => 'Teknik Informatika',
                'nim' => '21/234567/TK/12345',
                'semester' => 6,
                'profile_photo_url' => null
            ]
        ],
        'authenticated' => true
    ]);
    
    return redirect('/student/dashboard')->with('success', 'login berhasil sebagai mahasiswa (dev mode)');
})->name('dev.login.student');

// bypass login sebagai instansi
Route::get('/dev-login/institution', function () {
    session([
        'user' => [
            'id' => 2,
            'email' => 'admin@desamakmur.go.id',
            'username' => 'desamakmur',
            'user_type' => 'institution',
            'is_active' => true,
            'profile' => [
                'institution_name' => 'Pemerintah Desa Makmur',
                'institution_type' => 'pemerintah_desa',
                'pic_name' => 'Bapak Suharto',
                'pic_position' => 'Kepala Desa',
                'is_verified' => true,
                'logo_url' => null
            ]
        ],
        'authenticated' => true
    ]);
    
    return redirect('/institution/dashboard')->with('success', 'login berhasil sebagai instansi (dev mode)');
})->name('dev.login.institution');

// bypass login sebagai admin
Route::get('/dev-login/admin', function () {
    session([
        'user' => [
            'id' => 3,
            'email' => 'admin@kkngo.id',
            'username' => 'admin',
            'user_type' => 'admin',
            'is_active' => true,
            'profile' => [
                'name' => 'Admin KKN-GO',
            ]
        ],
        'authenticated' => true
    ]);
    
    return redirect('/admin/dashboard')->with('success', 'login berhasil sebagai admin (dev mode)');
})->name('dev.login.admin');

// logout dari dev session
Route::get('/dev-logout', function () {
    session()->flush();
    return redirect('/dev-login')->with('success', 'logout berhasil');
})->name('dev.logout');