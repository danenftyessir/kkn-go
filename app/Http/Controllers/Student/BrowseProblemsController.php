<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\Province;
use App\Models\Regency;
use App\Models\University;
use App\Models\Wishlist;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BrowseProblemsController extends Controller
{
    /**
     * tampilkan halaman browse problems
     */
    public function index(Request $request)
    {
        // query dengan SELECT specific columns only
        $query = Problem::select([
                'id', 
                'institution_id', 
                'province_id', 
                'regency_id', 
                'title', 
                'description',
                'status',
                'application_deadline',
                'required_students',
                'difficulty_level',
                'duration_months',
                'created_at'
            ])
            ->where('status', 'open')
            ->where('application_deadline', '>=', now());

        // search - simple query only
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // filter by regency
        if ($request->filled('regency_id')) {
            $query->where('regency_id', $request->regency_id);
        }

        // filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'deadline') {
            $query->orderBy('application_deadline', 'asc');
        } else {
            $query->latest();
        }

        // PAGINATION KECIL untuk performa (6 items per page)
        $problems = $query->paginate(6)->withQueryString();

        // eager load relasi SETELAH pagination (hanya 6 items)
        $problems->load([
            'institution:id,name,type,logo_path',
            'province:id,name',
            'regency:id,name',
            'images' => function($query) {
                $query->select('id', 'problem_id', 'image_path', 'is_cover', 'order', 'caption')
                      ->orderBy('is_cover', 'desc')
                      ->orderBy('order', 'asc');
            }
        ]);

        // accessor getCoverImageAttribute() dari Problem model akan otomatis bekerja
        // saat kita akses $problem->coverImage di blade
        
        // cek wishlist (hanya untuk user login)
        if (Auth::check() && Auth::user()->user_type === 'student') {
            $studentId = Auth::user()->student->id;
            $wishlistedIds = DB::table('wishlists')
                ->where('student_id', $studentId)
                ->pluck('problem_id')
                ->toArray();
            
            $problems->getCollection()->transform(function($problem) use ($wishlistedIds) {
                $problem->isWishlisted = in_array($problem->id, $wishlistedIds);
                return $problem;
            });
        }

        // data filter dengan cache
        $provinces = Cache::remember('provinces_list', 3600, function() {
            return Province::select('id', 'name')->orderBy('name')->get();
        });

        $regencies = [];
        if ($request->filled('province_id')) {
            $regencies = Cache::remember('regencies_province_' . $request->province_id, 3600, function() use ($request) {
                return Regency::select('id', 'name')
                    ->where('province_id', $request->province_id)
                    ->orderBy('name')
                    ->get();
            });
        }

        // statistics dengan cache
        $totalProblems = Cache::remember('total_open_problems', 300, function() {
            return Problem::where('status', 'open')
                ->where('application_deadline', '>=', now())
                ->count();
        });

        $openProblems = $totalProblems;

        $totalInstitutions = Cache::remember('total_active_institutions', 300, function() {
            return Institution::whereHas('problems', function($q) {
                $q->where('status', 'open')
                  ->where('application_deadline', '>=', now());
            })->count();
        });

        $universities = []; // kosongkan untuk sekarang, tidak perlu untuk filter basic

        return view('student.browse-problems.index', compact(
            'problems',
            'provinces',
            'regencies',
            'universities',
            'totalProblems',
            'openProblems',
            'totalInstitutions'
        ));
    }

    /**
     * tampilkan detail problem
     */
    public function show($id)
    {
        // query minimal dengan eager loading
        $problem = Problem::with([
                'institution:id,name,type,email,phone,address,description,logo_path',
                'province:id,name',
                'regency:id,name',
                'images' => function($query) {
                    $query->select('id', 'problem_id', 'image_path', 'is_cover', 'order', 'caption')
                          ->orderBy('is_cover', 'desc')
                          ->orderBy('order', 'asc');
                }
            ])
            ->findOrFail($id);

        // cek apakah user sudah apply (jika login sebagai student)
        $hasApplied = false;
        if (Auth::check() && Auth::user()->user_type === 'student') {
            $student = Auth::user()->student;
            $hasApplied = DB::table('applications')
                ->where('student_id', $student->id)
                ->where('problem_id', $id)
                ->exists();
        }

        return view('student.browse-problems.detail', compact('problem', 'hasApplied'));
    }
}