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
                'sdg_categories', // penting: harus di-select agar casting model ter-apply
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
        // jika user memilih satu atau lebih kategori SDG, tampilkan problem yang memiliki minimal salah satu kategori tersebut
        if ($request->filled('sdg_categories')) {
            $sdgCategories = $request->sdg_categories;
            
            // pastikan sdg_categories adalah array
            if (!is_array($sdgCategories)) {
                $sdgCategories = [$sdgCategories];
            }
            
            // filter menggunakan whereJsonContains untuk setiap kategori yang dipilih
            // problem akan muncul jika memiliki MINIMAL SATU dari kategori yang dipilih
            $query->where(function($q) use ($sdgCategories) {
                foreach ($sdgCategories as $category) {
                    // convert ke integer jika masih string
                    $category = is_numeric($category) ? (int)$category : $category;
                    $q->orWhereJsonContains('sdg_categories', $category);
                }
            });
        }

        // filter by duration
        if ($request->filled('duration')) {
            $duration = $request->duration;
            
            switch($duration) {
                case '1-2':
                    $query->whereBetween('duration_months', [1, 2]);
                    break;
                case '3-4':
                    $query->whereBetween('duration_months', [3, 4]);
                    break;
                case '5-6':
                    $query->whereBetween('duration_months', [5, 6]);
                    break;
                case '7+':
                    $query->where('duration_months', '>=', 7);
                    break;
            }
        }

        // filter by status
        if ($request->filled('status')) {
            $statusFilter = $request->status;
            
            // pastikan adalah array
            if (!is_array($statusFilter)) {
                $statusFilter = [$statusFilter];
            }
            
            // replace default status filter dengan yang user pilih
            $query->where(function($q) use ($statusFilter) {
                $q->whereIn('status', $statusFilter);
            });
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
                $query->select('id', 'problem_id', 'image_path')
                      ->orderBy('order')
                      ->limit(1);
            }
        ]);

        // data untuk filter sidebar
        $provinces = Province::orderBy('name')->get();
        
        // statistics untuk hero section
        $totalProblems = Problem::where('status', 'open')
            ->where('application_deadline', '>=', now())
            ->count();
        
        // hitung unique SDG categories dari semua problem yang open
        $totalCategories = Problem::where('status', 'open')
            ->where('application_deadline', '>=', now())
            ->get()
            ->pluck('sdg_categories')
            ->flatten()
            ->unique()
            ->count();

        return view('student.browse-problems.index', compact(
            'problems', 
            'provinces',
            'totalProblems',
            'totalCategories'
        ));
    }

    /**
     * tampilkan detail problem
     */
    public function show($id)
    {
        // ambil problem dengan relasi yang dibutuhkan
        $problem = Problem::with([
            'institution',
            'province',
            'regency',
            'images' => function($query) {
                $query->orderBy('order');
            }
        ])->findOrFail($id);

        // increment views count
        $problem->increment('views_count');

        // cek apakah user sudah apply
        $hasApplied = false;
        $application = null;
        
        if (Auth::check() && Auth::user()->role === 'student') {
            $application = Auth::user()->applications()
                ->where('problem_id', $problem->id)
                ->first();
            $hasApplied = $application !== null;
        }

        // cek wishlist
        $isWishlisted = false;
        if (Auth::check() && Auth::user()->role === 'student') {
            $isWishlisted = Wishlist::where('student_id', Auth::id())
                ->where('problem_id', $problem->id)
                ->exists();
        }

        // similar problems
        $similarProblems = Problem::where('id', '!=', $problem->id)
            ->where('status', 'open')
            ->where(function($query) use ($problem) {
                // cari problem dengan province yang sama atau SDG category yang sama
                $query->where('province_id', $problem->province_id)
                      ->orWhere(function($q) use ($problem) {
                          if ($problem->sdg_categories && count($problem->sdg_categories) > 0) {
                              foreach ($problem->sdg_categories as $sdg) {
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

        return view('student.browse-problems.show', compact(
            'problem',
            'hasApplied',
            'application',
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