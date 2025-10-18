<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * tampilkan halaman contact
     */
    public function index()
    {
        // data tim untuk ditampilkan di halaman contact
        $teamMembers = [
            [
                'name' => 'Kenzie Raffa Ardhana',
                'role' => 'Project Manager',
                'description' => 'Mengkoordinasi keseluruhan proyek dan memastikan semua berjalan sesuai rencana.',
                'photo' => 'profile_18223127.jpg',
                'email' => 'kenzie.raffa@example.com',
                'whatsapp' => '+62 812-3456-7890'
            ],
            [
                'name' => 'Danendra Shafi Athallah',
                'role' => 'Full Stack Developer',
                'description' => 'Mengembangkan frontend dan backend untuk memastikan sistem berjalan optimal.',
                'photo' => 'profile_13523136.jpg',
                'email' => 'danendra.shafi@example.com',
                'whatsapp' => '+62 812-3456-7891'
            ],
            [
                'name' => 'M. Abizzar Gamadrian',
                'role' => 'Backend Developer & Technical Support',
                'description' => 'Mengembangkan sistem backend dan memberikan dukungan teknis untuk pengguna.',
                'photo' => 'profile_13523155.jpg',
                'email' => 'abizzar.gamadrian@example.com',
                'whatsapp' => '+62 812-3456-7892'
            ],
        ];

        return view('contact.index', compact('teamMembers'));
    }
}