<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\Province;
use App\Models\Regency;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * controller untuk manage problems dari institusi
 * 
 * flow:
 * 1. institusi create problem (draft/open)
 * 2. problem bisa diedit/dihapus selama belum ada applications
 * 3. gambar disimpan ke Supabase Storage
 */
class ProblemController extends Controller
{
    protected $supabaseStorage;

    public function __construct(SupabaseStorageService $supabaseStorage)
    {
        $this->supabaseStorage = $supabaseStorage;
    }

    /**
     * tampilkan daftar problems milik institusi
     */
    public function index(Request $request)
    {
        $institution = auth()->user()->institution;
        
        $query = Problem::where('institution_id', $institution->id)
                       ->with(['province', 'regency', 'images'])
                       ->withCount('applications');

        // filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // search berdasarkan title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'most_applied':
                    $query->orderBy('applications_count', 'desc');
                    break;
                case 'latest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $problems = $query->paginate(10);

        // hitung statistik untuk cards
        $stats = [
            'total' => Problem::where('institution_id', $institution->id)->count(),
            'draft' => Problem::where('institution_id', $institution->id)->where('status', 'draft')->count(),
            'open' => Problem::where('institution_id', $institution->id)->where('status', 'open')->count(),
            'in_progress' => Problem::where('institution_id', $institution->id)->where('status', 'in_progress')->count(),
            'completed' => Problem::where('institution_id', $institution->id)->where('status', 'completed')->count(),
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
     */
    public function store(Request $request)
    {
        try {
            // preprocessing untuk array fields
            $requestData = $request->all();
            
            if (isset($requestData['required_skills'])) {
                if (is_string($requestData['required_skills'])) {
                    $requestData['required_skills'] = array_filter(
                        array_map('trim', explode(',', $requestData['required_skills']))
                    );
                }
            }
            
            if (isset($requestData['required_majors'])) {
                if (is_string($requestData['required_majors'])) {
                    $requestData['required_majors'] = array_filter(
                        array_map('trim', explode(',', $requestData['required_majors']))
                    );
                }
            }
            
            $request->merge($requestData);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'province_id' => 'required|integer|exists:provinces,id',
                'regency_id' => 'required|integer|exists:regencies,id',
                'address' => 'required|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
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
            
            // upload images ke supabase jika ada
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $imageFile) {
                    // upload ke supabase
                    $path = $this->supabaseStorage->uploadProblemImage(
                        $imageFile, 
                        $problem->id,
                        $index === 0  // image pertama jadi cover
                    );
                    
                    if ($path) {
                        $problem->images()->create([
                            'image_path' => $path,
                            'order' => $index + 1,
                            'is_cover' => $index === 0,
                        ]);
                        Log::info('Problem Store - Image Uploaded to Supabase', [
                            'path' => $path, 
                            'order' => $index + 1,
                            'is_cover' => $index === 0
                        ]);
                    } else {
                        Log::error('Problem Store - Image Upload Failed', [
                            'index' => $index,
                            'filename' => $imageFile->getClientOriginalName()
                        ]);
                    }
                }
            }

            DB::commit();

            Log::info('Problem Store - Success', ['problem_id' => $problem->id]);
            
            return redirect()
                ->route('institution.problems.show', $problem->id)
                ->with('success', 'Problem Berhasil Dibuat!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Problem Store - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
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
    public function update(Request $request, $id)
    {
        try {
            $problem = Problem::where('institution_id', auth()->user()->institution->id)
                            ->findOrFail($id);

            // preprocessing untuk array fields sama seperti store
            $requestData = $request->all();
            
            if (isset($requestData['required_skills'])) {
                if (is_string($requestData['required_skills'])) {
                    $requestData['required_skills'] = array_filter(
                        array_map('trim', explode(',', $requestData['required_skills']))
                    );
                }
            }
            
            if (isset($requestData['required_majors'])) {
                if (is_string($requestData['required_majors'])) {
                    $requestData['required_majors'] = array_filter(
                        array_map('trim', explode(',', $requestData['required_majors']))
                    );
                }
            }
            
            $request->merge($requestData);

            Log::info('Problem Update - Request Data', [
                'problem_id' => $id,
                'has_delete_images' => $request->filled('delete_images'),
                'delete_images_count' => $request->filled('delete_images') ? count($request->delete_images) : 0,
                'has_new_images' => $request->hasFile('images'),
                'new_images_count' => $request->hasFile('images') ? count($request->file('images')) : 0,
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'province_id' => 'required|integer|exists:provinces,id',
                'regency_id' => 'required|integer|exists:regencies,id',
                'address' => 'required|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
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
                'status' => 'required|in:draft,open,closed',
                'expected_outcomes' => 'nullable|string',
                'deliverables' => 'nullable|array',
                'facilities_provided' => 'nullable|array',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'integer|exists:problem_images,id',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]);

            DB::beginTransaction();
            
            // 1. update data problem terlebih dahulu
            $problem->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'province_id' => $validated['province_id'],
                'regency_id' => $validated['regency_id'],
                'address' => $validated['address'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'sdg_categories' => $validated['sdg_categories'],
                'required_students' => $validated['required_students'],
                'required_skills' => $validated['required_skills'],
                'required_majors' => $validated['required_majors'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'application_deadline' => $validated['application_deadline'],
                'duration_months' => $validated['duration_months'],
                'difficulty_level' => $validated['difficulty_level'],
                'status' => $validated['status'],
                'expected_outcomes' => $validated['expected_outcomes'] ?? null,
                'deliverables' => $validated['deliverables'] ?? null,
                'facilities_provided' => $validated['facilities_provided'] ?? null,
            ]);

            Log::info('Problem Update - Data Updated', ['problem_id' => $problem->id]);
            
            // 2. handle delete images jika ada
            if ($request->filled('delete_images') && is_array($request->delete_images)) {
                foreach ($request->delete_images as $imageId) {
                    try {
                        $image = $problem->images()->find($imageId);
                        if ($image) {
                            // hapus dari storage (supabase atau local)
                            try {
                                $this->supabaseStorage->delete($image->image_path);
                                Log::info('Problem Update - Image Deleted Successfully', [
                                    'image_id' => $imageId,
                                    'path' => $image->image_path
                                ]);
                            } catch (\Exception $deleteException) {
                                // jika gagal hapus dari storage, tetap lanjut hapus dari database
                                Log::warning('Problem Update - Failed to Delete Image from Storage', [
                                    'image_id' => $imageId,
                                    'path' => $image->image_path,
                                    'error' => $deleteException->getMessage()
                                ]);
                            }
                            
                            // hapus dari database
                            $image->delete();
                        }
                    } catch (\Exception $e) {
                        Log::error('Problem Update - Error Deleting Image', [
                            'image_id' => $imageId,
                            'error' => $e->getMessage()
                        ]);
                        // lanjutkan ke gambar berikutnya, jangan stop
                    }
                }
            }
            
            // 3. upload gambar baru ke supabase jika ada
            if ($request->hasFile('images')) {
                // refresh data problem untuk get jumlah gambar terbaru setelah delete
                $problem->refresh();
                $currentImageCount = $problem->images()->count();
                
                Log::info('Problem Update - Current Image Count', ['count' => $currentImageCount]);
                
                foreach ($request->file('images') as $index => $imageFile) {
                    try {
                        // upload ke supabase
                        $path = $this->supabaseStorage->uploadProblemImage(
                            $imageFile,
                            $problem->id,
                            $currentImageCount === 0 && $index === 0  // jadi cover jika belum ada gambar
                        );
                        
                        if ($path) {
                            $problem->images()->create([
                                'image_path' => $path,
                                'order' => $currentImageCount + $index + 1,
                                'is_cover' => $currentImageCount === 0 && $index === 0,
                            ]);
                            
                            Log::info('Problem Update - Image Uploaded Successfully', [
                                'path' => $path,
                                'order' => $currentImageCount + $index + 1,
                                'is_cover' => $currentImageCount === 0 && $index === 0
                            ]);
                        } else {
                            Log::error('Problem Update - Image Upload Failed', [
                                'index' => $index,
                                'filename' => $imageFile->getClientOriginalName()
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Problem Update - Error Uploading Image', [
                            'index' => $index,
                            'error' => $e->getMessage()
                        ]);
                        // lanjutkan ke gambar berikutnya, jangan stop
                    }
                }
            }

            DB::commit();

            Log::info('Problem Update - Success', ['problem_id' => $problem->id]);
            
            return redirect()
                ->route('institution.problems.show', $problem->id)
                ->with('success', 'Problem Berhasil Diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Problem Update - Validation Error', [
                'errors' => $e->validator->errors()->toArray()
            ]);
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Problem Update - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * hapus problem
     */
    public function destroy($id)
    {
        try {
            $problem = Problem::where('institution_id', auth()->user()->institution->id)
                             ->findOrFail($id);
            
            // cek apakah ada aplikasi yang sudah diterima
            if ($problem->applications()->where('status', 'accepted')->exists()) {
                return back()->with('error', 'Tidak Dapat Menghapus Problem yang Sudah Memiliki Aplikasi Diterima!');
            }
            
            DB::beginTransaction();
            
            // hapus semua gambar dari supabase
            foreach ($problem->images as $image) {
                $this->supabaseStorage->delete($image->image_path);
                $image->delete();
                Log::info('Problem Delete - Image Deleted from Supabase', ['path' => $image->image_path]);
            }
            
            $problem->delete();
            
            DB::commit();
            
            return redirect()
                ->route('institution.problems.index')
                ->with('success', 'Problem Berhasil Dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Problem Delete - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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