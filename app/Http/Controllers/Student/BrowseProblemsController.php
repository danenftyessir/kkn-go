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
    // ============ LANGKAH DEBUGGING TERAKHIR & PALING DASAR ============

    // 1. Kita mulai dengan query yang benar-benar kosong, tanpa filter apapun.
    $query = \App\Models\Problem::query(); 
    
    // 2. Kita terapkan HANYA satu kondisi: cari problem yang sdg_categories-nya
    //    mengandung string "12". Nilainya kita tulis langsung (hardcode).
    $query->whereRaw('jsonb_exists(sdg_categories::jsonb, ?)', ['12']);

    // 3. Langsung ambil hasilnya dari database.
    $problemsFound = $query->get();

    // 4. Hentikan semua proses dan tampilkan hasilnya secara paksa.
    dd(
        "SQL mentah yang dikirim ke database:",
        $query->toSql(),
        "Nilai yang dicari:",
        $query->getBindings(),
        "Jumlah hasil yang ditemukan:",
        $problemsFound->count(),
        "Data lengkap yang ditemukan:",
        $problemsFound
    );
    
    // ====================================================================

    // Baris-baris di bawah ini tidak akan pernah dijalankan selama dd() aktif.
    return view('student.browse-problems.index');
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