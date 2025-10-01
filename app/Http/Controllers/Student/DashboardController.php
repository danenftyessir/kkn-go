<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * tampilkan dashboard mahasiswa
     */
    public function index()
    {
        // TODO: implementasi dashboard lengkap
        
        // untuk sementara redirect ke browse problems
        return redirect()->route('student.browse-problems');
    }
}