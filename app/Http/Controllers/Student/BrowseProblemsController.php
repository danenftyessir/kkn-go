<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\Province;
use App\Models\Regency;
use App\Models\University;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class BrowseProblemsController extends Controller
{
    /**
     * tampilkan halaman browse problems
     * mendukung ajax request untuk filtering
     */
    public function index(Request $request)
    {
        // query builder untuk problems
        $query = Problem::with(['institution', 'province', 'regency', 'images'])
                       ->where('status', 'open');

        // search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('institution', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
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

        // filter by SDG categories (multiple)
        if ($request->filled('sdg_categories')) {
            $sdgCategories = $request->sdg_categories;
            $query->where(function($q) use ($sdgCategories) {
                foreach ($sdgCategories as $sdg) {
                    $q->orWhereJsonContains('sdg_categories', $sdg);
                }
            });
        }

        // filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // filter by duration
        if ($request->filled('duration')) {
            switch ($request->duration) {
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

        // filter by status (multiple)
        if ($request->filled('status')) {
            $query->whereIn('status', $request->status);
        }

        // sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'most_applied':
                $query->withCount('applications')->orderBy('applications_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // pagination
        $problems = $query->paginate(12)->withQueryString();

        // tambahkan informasi wishlist untuk setiap problem jika user sudah login
        if (Auth::check() && Auth::user()->user_type === 'student') {
            $studentId = Auth::user()->student->id;
            $wishlistedProblemIds = Wishlist::where('student_id', $studentId)
                                            ->pluck('problem_id')
                                            ->toArray();
            
            // tambahkan attribute wishlisted ke setiap problem
            $problems->getCollection()->transform(function ($problem) use ($wishlistedProblemIds) {
                $problem->wishlisted = in_array($problem->id, $wishlistedProblemIds);
                return $problem;
            });
        } else {
            // jika tidak login, set semua wishlisted = false
            $problems->getCollection()->transform(function ($problem) {
                $problem->wishlisted = false;
                return $problem;
            });
        }

        // data untuk filter
        $provinces = Cache::remember('provinces', 3600, function () {
            return Province::orderBy('name')->get();
        });

        $regencies = [];
        if ($request->filled('province_id')) {
            $regencies = Regency::where('province_id', $request->province_id)
                               ->orderBy('name')
                               ->get();
        }

        $universities = Cache::remember('universities', 3600, function () {
            return University::orderBy('name')->get();
        });

        // statistics
        $stats = [
            'total' => $problems->total(),
            'open' => Problem::where('status', 'open')->count(),
            'in_progress' => Problem::where('status', 'in_progress')->count(),
        ];

        return view('student.browse-problems.index', compact(
            'problems',
            'provinces',
            'regencies',
            'universities',
            'stats'
        ));
    }

    /**
     * tampilkan detail problem
     */
    public function show($id)
    {
        $problem = Problem::with([
            'institution',
            'province',
            'regency',
            'images',
            'applications' => function($query) {
                $query->latest()->limit(5);
            }
        ])->findOrFail($id);

        // increment views
        $problem->increment('views_count');

        // cek apakah user sudah apply
        $hasApplied = false;
        $isWishlisted = false;
        
        if (Auth::check() && Auth::user()->user_type === 'student') {
            $studentId = Auth::user()->student->id;
            
            // cek apakah sudah apply
            $hasApplied = $problem->applications()
                                 ->where('student_id', $studentId)
                                 ->exists();
            
            // cek apakah sudah di-wishlist
            $isWishlisted = Wishlist::where('student_id', $studentId)
                                   ->where('problem_id', $problem->id)
                                   ->exists();
        }

        // similar problems berdasarkan lokasi dan kategori SDG
        // decode sdg_categories jika masih string
        $sdgCategories = $problem->sdg_categories;
        if (is_string($sdgCategories)) {
            $sdgCategories = json_decode($sdgCategories, true) ?? [];
        }
        
        $similarProblems = Problem::with(['institution', 'province', 'regency', 'images'])
            ->where('status', 'open')
            ->where('id', '!=', $problem->id)
            ->where(function($query) use ($problem, $sdgCategories) {
                $query->where('province_id', $problem->province_id);
                
                // tambahkan filter berdasarkan SDG categories jika ada
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
        $regencies = Regency::where('province_id', $provinceId)
                           ->orderBy('name')
                           ->get(['id', 'name']);
        
        return response()->json($regencies);
    }
}