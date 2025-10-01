<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\Province;
use App\Models\Regency;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrowseProblemsController extends Controller
{
    /**
     * tampilkan halaman browse problems
     */
    public function index(Request $request)
    {
        // TODO: implementasi caching untuk filter options
        $query = Problem::with(['institution', 'province', 'regency', 'images'])
                       ->open();

        // search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // filter by regency
        if ($request->filled('regency_id')) {
            $query->where('regency_id', $request->regency_id);
        }

        // filter by SDG category
        if ($request->filled('sdg')) {
            $query->whereJsonContains('sdg_categories', (int)$request->sdg);
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

        // filter by university partner (jika instansi mencari kerjasama dengan universitas tertentu)
        // TODO: implementasi relasi university partner di tabel problems
        if ($request->filled('university_id')) {
            // query untuk filter university akan ditambahkan ketika relasi sudah dibuat
        }

        // sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'deadline':
                $query->orderBy('application_deadline', 'asc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'most_applied':
                $query->orderBy('applications_count', 'desc');
                break;
        }

        // featured problems di top
        $query->orderBy('is_featured', 'desc')
              ->orderBy('is_urgent', 'desc');

        // pagination
        $problems = $query->paginate(12)->withQueryString();

        // data untuk filter
        $provinces = Province::orderBy('name')->get();
        $regencies = collect();
        
        if ($request->filled('province_id')) {
            $regencies = Regency::where('province_id', $request->province_id)
                               ->orderBy('name')
                               ->get();
        }

        $universities = University::orderBy('name')->get();

        // statistik untuk dashboard info
        $stats = [
            'total_problems' => Problem::open()->count(),
            'total_slots' => Problem::open()->sum('required_students'),
            'urgent_count' => Problem::open()->where('is_urgent', true)->count(),
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
        $problem->incrementViews();

        // cek apakah user sudah apply
        $hasApplied = false;
        if (auth()->check() && auth()->user()->student) {
            $hasApplied = $problem->applications()
                                 ->where('student_id', auth()->user()->student->id)
                                 ->exists();
        }

        // similar problems berdasarkan lokasi dan kategori SDG
        $similarProblems = Problem::open()
            ->where('id', '!=', $problem->id)
            ->where(function($query) use ($problem) {
                $query->where('province_id', $problem->province_id)
                      ->orWhereJsonContains('sdg_categories', $problem->sdg_categories[0] ?? null);
            })
            ->limit(3)
            ->get();

        // TODO: implementasi Q&A section dari database
        // TODO: implementasi wishlist check

        return view('student.browse-problems.detail', compact(
            'problem',
            'hasApplied',
            'similarProblems'
        ));
    }

    /**
     * get regencies by province (AJAX)
     */
    public function getRegencies($provinceId)
    {
        $regencies = Regency::where('province_id', $provinceId)
                           ->orderBy('name')
                           ->get(['id', 'name']);
        
        return response()->json($regencies);
    }
}