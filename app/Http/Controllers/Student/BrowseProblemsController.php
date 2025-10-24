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
        // tambahkan sdg_categories ke SELECT agar bisa difilter
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
                'sdg_categories',
                'is_featured',
                'is_urgent',
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

        // filter by SDG categories
        if ($request->filled('sdg_categories')) {
            $sdgCategories = $request->sdg_categories;
            
            if (!is_array($sdgCategories)) {
                $sdgCategories = [$sdgCategories];
            }
            
            $query->where(function($q) use ($sdgCategories) {
                foreach ($sdgCategories as $category) {
                    $category = is_numeric($category) ? (int)$category : $category;
                    $q->orWhereJsonContains('sdg_categories', $category);
                }
            });
        }

        // filter by duration
        if ($request->filled('duration')) {
            $duration = $request->duration;
            
            if ($duration === '1-2') {
                $query->whereBetween('duration_months', [1, 2]);
            } elseif ($duration === '3-4') {
                $query->whereBetween('duration_months', [3, 4]);
            } elseif ($duration === '5-6') {
                $query->whereBetween('duration_months', [5, 6]);
            }
        }

        // sorting
        $sortBy = $request->get('sort', 'latest');
        
        switch ($sortBy) {
            case 'deadline':
                $query->orderBy('application_deadline', 'asc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // eager load relationships - optimized
        $query->with([
            'institution:id,name,type,logo_path',
            'province:id,name',
            'regency:id,name,province_id',
            'images' => function($query) {
                $query->select('id', 'problem_id', 'image_path', 'order')
                      ->orderBy('order')
                      ->limit(1);
            }
        ]);

        // paginate 12 items per page
        $problems = $query->paginate(12)->withQueryString();

        // data untuk filter dropdowns
        $provinces = Province::orderBy('name')->get(['id', 'name']);
        $regencies = [];
        
        if ($request->filled('province_id')) {
            $regencies = Regency::where('province_id', $request->province_id)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return view('student.browse-problems.index', compact(
            'problems',
            'provinces',
            'regencies'
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
            'images' => function($query) {
                $query->orderBy('order');
            }
        ])->findOrFail($id);

        // increment views
        $problem->increment('views_count');

        // cek apakah user sudah apply
        $hasApplied = false;
        if (Auth::check() && Auth::user()->isStudent() && Auth::user()->student) {
            $hasApplied = Auth::user()->student->applications()
                ->where('problem_id', $problem->id)
                ->exists();
        }

        // cek apakah user sudah wishlist
        $isWishlisted = false;
        if (Auth::check() && Auth::user()->isStudent() && Auth::user()->student) {
            $isWishlisted = Auth::user()->student->hasWishlisted($problem->id);
        }

        // similar problems berdasarkan lokasi dan kategori SDG
        $similarProblems = Problem::where('id', '!=', $problem->id)
            ->where('status', 'open')
            ->where(function($query) use ($problem) {
                // cari problem dengan province yang sama atau SDG category yang sama
                $query->where('province_id', $problem->province_id)
                      ->orWhere(function($q) use ($problem) {
                          // pastikan sdg_categories adalah array dan tidak kosong
                          $sdgCategories = $problem->sdg_categories;
                          
                          // konversi ke array jika string
                          if (is_string($sdgCategories)) {
                              $sdgCategories = json_decode($sdgCategories, true) ?? [];
                          }
                          
                          // pastikan array dan tidak kosong
                          if (is_array($sdgCategories) && count($sdgCategories) > 0) {
                              foreach ($sdgCategories as $sdg) {
                                  $q->orWhereJsonContains('sdg_categories', $sdg);
                              }
                          }
                      });
            })
            ->with(['institution', 'province', 'regency', 'images' => function($query) {
                $query->orderBy('order')->limit(1);
            }])
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
     * get regencies by province (untuk filter dinamis)
     */
    public function getRegencies(Request $request)
    {
        $provinceId = $request->province_id;
        
        if (!$provinceId) {
            return response()->json([]);
        }

        $regencies = Regency::where('province_id', $provinceId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($regencies);
    }
}