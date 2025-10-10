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
     * dengan optimasi query untuk performa tinggi
     */
    public function index(Request $request)
    {
        // query builder dengan eager loading optimal
        $query = Problem::with(['institution:id,name,type,logo_path', 'province:id,name', 'regency:id,name'])
                       ->select('id', 'institution_id', 'province_id', 'regency_id', 'title', 'description', 
                                'status', 'required_students', 'difficulty_level', 'application_deadline', 
                                'duration_months', 'sdg_categories', 'created_at')
                       ->where('status', 'open')
                       ->where('application_deadline', '>=', now());

        // search
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

        // filter by SDG categories
        if ($request->filled('sdg_categories')) {
            $sdgCategories = is_array($request->sdg_categories) 
                ? $request->sdg_categories 
                : [$request->sdg_categories];
            
            $query->where(function($q) use ($sdgCategories) {
                foreach ($sdgCategories as $sdg) {
                    $q->orWhereJsonContains('sdg_categories', (int)$sdg);
                }
            });
        }

        // filter by duration
        if ($request->filled('duration')) {
            $duration = $request->duration;
            switch ($duration) {
                case '1-2':
                    $query->whereBetween('duration_months', [1, 2]);
                    break;
                case '3-4':
                    $query->whereBetween('duration_months', [3, 4]);
                    break;
                case '5-6':
                    $query->whereBetween('duration_months', [5, 6]);
                    break;
            }
        }

        // filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // filter by university partner (jika ada)
        if ($request->filled('university_id')) {
            $query->whereJsonContains('partner_universities', (int)$request->university_id);
        }

        // sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'deadline':
                $query->orderBy('application_deadline', 'asc');
                break;
            case 'popular':
                $query->withCount('applications')
                      ->orderBy('applications_count', 'desc');
                break;
            default: // latest
                $query->latest();
        }

        // pagination dengan limit kecil untuk performa
        $problems = $query->paginate(12)->withQueryString();

        // load images hanya untuk problems yang ditampilkan (lazy load)
        $problems->load(['images' => function($query) {
            $query->where('is_cover', true)->orWhere('order', 1)->limit(1);
        }]);

        // cek wishlist status untuk user yang login
        if (Auth::check() && Auth::user()->user_type === 'student') {
            $studentId = Auth::user()->student->id;
            $wishlistedIds = Wishlist::where('student_id', $studentId)
                                     ->pluck('problem_id')
                                     ->toArray();
            
            $problems->getCollection()->transform(function ($problem) use ($wishlistedIds) {
                $problem->isWishlisted = in_array($problem->id, $wishlistedIds);
                return $problem;
            });
        }

        // data untuk filter (dengan caching)
        $provinces = Cache::remember('provinces_list', 3600, function () {
            return Province::select('id', 'name')->orderBy('name')->get();
        });

        $regencies = [];
        if ($request->filled('province_id')) {
            $regencies = Cache::remember('regencies_' . $request->province_id, 3600, function () use ($request) {
                return Regency::select('id', 'name')
                             ->where('province_id', $request->province_id)
                             ->orderBy('name')
                             ->get();
            });
        }

        $universities = Cache::remember('universities_list', 3600, function () {
            return University::select('id', 'name')->orderBy('name')->get();
        });

        // statistics (dengan caching untuk performa)
        $totalProblems = Cache::remember('stats_total_problems', 300, function () {
            return Problem::where('status', 'open')
                         ->where('application_deadline', '>=', now())
                         ->count();
        });

        $openProblems = $totalProblems; // sama dengan total

        $totalInstitutions = Cache::remember('stats_total_institutions', 300, function () {
            return Institution::whereHas('problems', function($q) {
                $q->where('status', 'open')
                  ->where('application_deadline', '>=', now());
            })->count();
        });

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
        $problem = Problem::with([
            'institution:id,name,type,address,phone,email,logo_path,description',
            'province:id,name',
            'regency:id,name',
            'images'
        ])->findOrFail($id);

        // increment views (async, tidak block response)
        DB::table('problems')->where('id', $id)->increment('views_count');

        // cek status untuk user yang login
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

        // similar problems dengan limit kecil
        $sdgCategories = is_string($problem->sdg_categories) 
            ? json_decode($problem->sdg_categories, true) 
            : $problem->sdg_categories;
        
        $similarProblems = Problem::with(['institution:id,name,type,logo_path', 'province:id,name', 'regency:id,name'])
            ->select('id', 'institution_id', 'province_id', 'regency_id', 'title', 
                     'description', 'application_deadline', 'required_students', 'difficulty_level')
            ->where('status', 'open')
            ->where('id', '!=', $id)
            ->where('application_deadline', '>=', now())
            ->where(function($query) use ($problem, $sdgCategories) {
                $query->where('province_id', $problem->province_id);
                
                if (!empty($sdgCategories) && is_array($sdgCategories)) {
                    $query->orWhere(function($q) use ($sdgCategories) {
                        foreach ($sdgCategories as $sdg) {
                            $q->orWhereJsonContains('sdg_categories', $sdg);
                        }
                    });
                }
            })
            ->limit(3)
            ->get();

        // load images untuk similar problems
        $similarProblems->load(['images' => function($query) {
            $query->where('is_cover', true)->orWhere('order', 1)->limit(1);
        }]);

        return view('student.browse-problems.detail', compact(
            'problem',
            'hasApplied',
            'isWishlisted',
            'similarProblems'
        ));
    }

    /**
     * get regencies by province (untuk AJAX)
     */
    public function getRegencies($provinceId)
    {
        $regencies = Cache::remember('regencies_' . $provinceId, 3600, function () use ($provinceId) {
            return Regency::select('id', 'name')
                         ->where('province_id', $provinceId)
                         ->orderBy('name')
                         ->get();
        });
        
        return response()->json($regencies);
    }
}