<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * controller untuk manajemen problems oleh institution
 */
class ProblemController extends Controller
{
    /**
     * tampilkan daftar problems yang dibuat institution
     */
    public function index(Request $request)
    {
        $institution = auth()->user()->institution;
        
        $query = Problem::where('institution_id', $institution->id)
                       ->with(['province', 'regency', 'images']);
        
        // filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title':
                $query->orderBy('title');
                break;
            case 'applications':
                $query->orderBy('applications_count', 'desc');
                break;
            case 'views':
                $query->orderBy('views_count', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $problems = $query->paginate(12)->withQueryString();
        
        // statistik
        $stats = [
            'total' => Problem::where('institution_id', $institution->id)->count(),
            'draft' => Problem::where('institution_id', $institution->id)->where('status', 'draft')->count(),
            'open' => Problem::where('institution_id', $institution->id)->where('status', 'open')->count(),
            'in_progress' => Problem::where('institution_id', $institution->id)->where('status', 'in_progress')->count(),
            'completed' => Problem::where('institution_id', $institution->id)->where('status', 'completed')->count(),
            'closed' => Problem::where('institution_id', $institution->id)->where('status', 'closed')->count(),
        ];
        
        return view('institution.problems.index', compact('problems', 'stats'));
    }
    
    /**
     * tampilkan form create problem
     */
    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        return view('institution.problems.create', compact('provinces'));
    }
    
    /**
     * simpan problem baru
     * FIX: handle array conversion untuk required_skills dan sdg_categories
     */
    public function store(Request $request)
    {
        try {
            Log::info('Problem Store - Request Data', [
                'all_data' => $request->except(['images']),
                'has_images' => $request->hasFile('images')
            ]);

            // preprocessing: convert string ke array untuk required_skills jika perlu
            $requestData = $request->all();
            
            // handle required_skills: jika string dengan koma, split jadi array
            if (isset($requestData['required_skills'])) {
                if (is_string($requestData['required_skills'])) {
                    $requestData['required_skills'] = array_filter(
                        array_map('trim', explode(',', $requestData['required_skills']))
                    );
                } elseif (!is_array($requestData['required_skills'])) {
                    $requestData['required_skills'] = [];
                }
                // remove empty values
                $requestData['required_skills'] = array_values(array_filter($requestData['required_skills']));
            }

            // handle required_majors: sama seperti required_skills
            if (isset($requestData['required_majors'])) {
                if (is_string($requestData['required_majors']) && !empty($requestData['required_majors'])) {
                    $requestData['required_majors'] = array_filter(
                        array_map('trim', explode(',', $requestData['required_majors']))
                    );
                } elseif (!is_array($requestData['required_majors'])) {
                    $requestData['required_majors'] = [];
                }
                $requestData['required_majors'] = array_values(array_filter($requestData['required_majors']));
            }

            // handle deliverables: clean up array
            if (isset($requestData['deliverables']) && is_array($requestData['deliverables'])) {
                $requestData['deliverables'] = array_values(array_filter($requestData['deliverables']));
            }

            // handle facilities_provided: clean up array
            if (isset($requestData['facilities_provided']) && is_array($requestData['facilities_provided'])) {
                $requestData['facilities_provided'] = array_values(array_filter($requestData['facilities_provided']));
            }

            // handle sdg_categories: convert ke integer array
            if (isset($requestData['sdg_categories']) && is_array($requestData['sdg_categories'])) {
                $requestData['sdg_categories'] = array_values(array_map('intval', $requestData['sdg_categories']));
            }

            // replace request dengan data yang sudah diprocess
            $request->merge($requestData);

            Log::info('Problem Store - After Preprocessing', [
                'required_skills' => $requestData['required_skills'] ?? null,
                'required_majors' => $requestData['required_majors'] ?? null,
                'sdg_categories' => $requestData['sdg_categories'] ?? null,
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'background' => 'nullable|string',
                'objectives' => 'nullable|string',
                'scope' => 'nullable|string',
                'province_id' => 'required|exists:provinces,id',
                'regency_id' => 'required|exists:regencies,id',
                'village' => 'nullable|string|max:255',
                'detailed_location' => 'nullable|string',
                'sdg_categories' => 'required|array|min:1',
                'sdg_categories.*' => 'integer|between:1,17',
                'required_students' => 'required|integer|min:1',
                'required_skills' => 'required|array|min:1',
                'required_majors' => 'nullable|array',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'application_deadline' => 'required|date|before:start_date',
                'duration_months' => 'required|integer|min:1',
                'difficulty_level' => 'required|in:beginner,intermediate,advanced',
                'status' => 'required|in:draft,open',
                'expected_outcomes' => 'nullable|string',
                'deliverables' => 'nullable|array',
                'facilities_provided' => 'nullable|array',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]);

            Log::info('Problem Store - Validation Passed', ['validated' => $validated]);

            DB::beginTransaction();

            $validated['institution_id'] = auth()->user()->institution->id;
            
            $problem = Problem::create($validated);

            Log::info('Problem Store - Problem Created', ['problem_id' => $problem->id]);
            
            // upload images jika ada
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('problems', 'public');
                    $problem->images()->create([
                        'image_path' => $path,
                        'order' => $index + 1,
                        'is_cover' => $index === 0, // image pertama jadi cover
                    ]);
                    Log::info('Problem Store - Image Uploaded', ['path' => $path, 'order' => $index + 1]);
                }
            }

            DB::commit();

            Log::info('Problem Store - Success', ['problem_id' => $problem->id]);
            
            return redirect()
                ->route('institution.problems.show', $problem->id)
                ->with('success', 'Problem Berhasil Dibuat!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Problem Store - Validation Error', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Problem Store - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan problem: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * tampilkan detail problem
     */
    public function show($id)
    {
        $problem = Problem::where('institution_id', auth()->user()->institution->id)
                         ->with(['province', 'regency', 'images', 'applications'])
                         ->findOrFail($id);
        
        return view('institution.problems.show', compact('problem'));
    }
    
    /**
     * tampilkan form edit problem
     */
    public function edit($id)
    {
        $problem = Problem::where('institution_id', auth()->user()->institution->id)
                         ->with(['images'])
                         ->findOrFail($id);
        
        $provinces = Province::orderBy('name')->get();
        $regencies = Regency::where('province_id', $problem->province_id)
                           ->orderBy('name')
                           ->get();
        
        return view('institution.problems.edit', compact('problem', 'provinces', 'regencies'));
    }
    
    /**
     * update problem
     */
    public function update(Request $request, Problem $problem)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'problem_domain' => 'required|string',
            'duration_months' => 'required|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Izinkan null jika tidak ada perubahan gambar
            'delete_images' => 'nullable|array', // Untuk menandai gambar yang ingin dihapus
            'delete_images.*' => 'exists:problem_images,id', // Validasi ID gambar yang akan dihapus
        ]);

        // Update data problem lainnya
        $problem->update($request->except(['_token', '_method', 'images', 'delete_images']));

        // --- Logika untuk Gambar ---

        // 1. Hapus Gambar yang Ditandai untuk Dihapus
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $imageToDelete = $problem->images()->find($imageId);
                if ($imageToDelete) {
                    // Hapus dari storage (misal: Supabase Storage)
                    Storage::disk('public')->delete($imageToDelete->image_path);
                    // Hapus dari database
                    $imageToDelete->delete();
                }
            }
        }

        // 2. Upload Gambar Baru
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                // Hapus gambar cover lama jika ada gambar baru yang diupload dan ini adalah gambar pertama
                // Ini adalah pendekatan "replace all" atau menambahkan. Jika ingin mengganti cover, logika akan lebih kompleks.
                // Untuk sederhana, kita tambahkan saja gambar baru.
                // Jika Anda hanya ingin 1 gambar (seperti profil), Anda perlu hapus semua gambar lama dan upload yang baru.
                // Mari kita asumsikan multiple images, tapi yang pertama akan jadi cover.

                $path = $image->store('problems', 'public'); // 'problems' adalah folder di Supabase Storage Anda

                $problem->images()->create([
                    'image_path' => $path,
                    'order' => $problem->images()->count() + 1, // Sesuaikan order
                    'is_cover' => ($problem->images()->count() === 0 && $index === 0) // Jika ini gambar pertama dan belum ada gambar lain, jadi cover
                ]);
            }
            // Jika ada gambar baru diupload, dan belum ada cover, set gambar pertama yang baru sebagai cover
            if ($problem->images()->where('is_cover', true)->doesntExist()) {
                $firstImage = $problem->images()->orderBy('order')->first();
                if ($firstImage) {
                    $firstImage->update(['is_cover' => true]);
                }
            }
        }

        // Jika tidak ada gambar baru diupload dan semua gambar lama dihapus, pastikan tidak ada cover yang tersisa
        if ($problem->images()->where('is_cover', true)->doesntExist() && $problem->images()->count() > 0) {
            $firstImage = $problem->images()->orderBy('order')->first();
            if ($firstImage) {
                $firstImage->update(['is_cover' => true]);
            }
        }


        return redirect()->route('institution.problems.show', $problem->id)->with('success', 'Problem berhasil diperbarui.');
    }
    
    /**
     * hapus problem
     */
    public function destroy($id)
    {
        $problem = Problem::where('institution_id', auth()->user()->institution->id)
                         ->findOrFail($id);
        
        // cek apakah ada aplikasi yang sudah diterima
        if ($problem->applications()->where('status', 'accepted')->exists()) {
            return back()->with('error', 'Tidak Dapat Menghapus Problem yang Sudah Memiliki Aplikasi Diterima!');
        }
        
        // hapus semua gambar
        foreach ($problem->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        
        $problem->delete();
        
        return redirect()
            ->route('institution.problems.index')
            ->with('success', 'Problem Berhasil Dihapus!');
    }

    /**
     * API endpoint untuk mendapatkan regencies berdasarkan province
     * digunakan untuk dynamic dropdown
     */
    public function getRegencies($provinceId)
    {
        $regencies = Regency::where('province_id', $provinceId)
                           ->orderBy('name')
                           ->get(['id', 'name']);
        
        return response()->json($regencies);
    }
}