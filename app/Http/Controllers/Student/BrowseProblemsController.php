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
     * ULTRA OPTIMIZED untuk performa maksimal
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
            'regency:id,name'
        ]);

        // load 1 cover image saja per problem (lazy load)
        $problemIds = $problems->pluck('id')->toArray();
        $coverImages = DB::table('problem_images')
            ->whereIn('problem_id', $problemIds)
            ->where(function($q) {
                $q->where('is_cover', true)
                  ->orWhere('order', 1);
            })
            ->groupBy('problem_id')
            ->get()
            ->keyBy('problem_id');

        // attach images to problems
        $problems->getCollection()->transform(function($problem) use ($coverImages) {
            $problem->cover_image = $coverImages->get($problem->id);
            return $problem;
        });

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
        // query minimal
        $problem = Problem::select([
                'id',
                'institution_id',
                'province_id',
                'regency_id',
                'title',
                'description',
                'background',
                'objectives',
                'scope',
                'status',
                'application_deadline',
                'start_date',
                'end_date',
                'required_students',
                'required_skills',
                'required_majors',
                'difficulty_level',
                'duration_months',
                'expected_outcomes',
                'deliverables',
                'facilities_provided',
                'sdg_categories',
                'created_at'
            ])
            ->findOrFail($id);

        // load relasi setelah find (lebih efisien)
        $problem->load([
            'institution:id,name,type,address,phone,email,logo_path,description',
            'province:id,name',
            'regency:id,name',
            'images'
        ]);

        // increment views (async, tidak block)
        DB::table('problems')->where('id', $id)->increment('views_count');

        // cek status
        $hasApplied = false;
        $isWishlisted = false;
        
        if (Auth::check() && Auth::user()->user_type === 'student') {
            $studentId = Auth::user()->student->id;
            
            $hasApplied = DB::table('applications')
                ->where('student_id', $studentId)
                ->where('problem_id', $id)
                ->exists();
            
            $isWishlisted = DB::table('wishlists')
                ->where('student_id', $studentId)
                ->where('problem_id', $id)
                ->exists();
        }

        // similar problems - LIMIT KECIL (3 items)
        $similarProblems = Problem::select(['id', 'institution_id', 'title', 'description', 'application_deadline'])
            ->where('status', 'open')
            ->where('id', '!=', $id)
            ->where('application_deadline', '>=', now())
            ->where('province_id', $problem->province_id)
            ->limit(3)
            ->get();

        $similarProblems->load([
            'institution:id,name,type,logo_path',
            'images' => function($q) {
                $q->where('is_cover', true)->orWhere('order', 1)->limit(1);
            }
        ]);

        return view('student.browse-problems.detail', compact(
            'problem',
            'hasApplied',
            'isWishlisted',
            'similarProblems'
        ));
    }

    /**
     * get regencies by province (AJAX)
     */
    public function getRegencies($provinceId)
    {
        $regencies = Cache::remember('regencies_api_' . $provinceId, 3600, function() use ($provinceId) {
            return Regency::select('id', 'name')
                ->where('province_id', $provinceId)
                ->orderBy('name')
                ->get();
        });
        
        return response()->json($regencies);
    }
}