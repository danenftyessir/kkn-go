<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Support\Facades\Storage;

/**
 * controller untuk manajemen problems oleh institution
 */
class ProblemController extends Controller
{
    /**
     * ✅ TAMBAHAN BARU: tampilkan daftar problems yang dibuat institution
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
     */
    public function store(Request $request)
    {
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
        
        $validated['institution_id'] = auth()->user()->institution->id;
        
        $problem = Problem::create($validated);
        
        // upload images jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('problems', 'public');
                $problem->images()->create([
                    'image_path' => $path,
                    'order' => $index + 1,
                ]);
            }
        }
        
        return redirect()
            ->route('institution.problems.show', $problem->id)
            ->with('success', 'Problem berhasil dibuat!');
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
        $problem = Problem::where('institution_id', auth()->user()->institution->id)
                         ->findOrFail($id);
        
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
            'required_students' => 'required|integer|min:1',
            'required_skills' => 'required|array|min:1',
            'required_majors' => 'nullable|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'application_deadline' => 'required|date|before:start_date',
            'duration_months' => 'required|integer|min:1',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'status' => 'required|in:draft,open,in_progress,completed,closed',
            'expected_outcomes' => 'nullable|string',
            'deliverables' => 'nullable|array',
            'facilities_provided' => 'nullable|array',
            'delete_images' => 'nullable|array',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        // hapus gambar yang dipilih untuk dihapus
        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = $problem->images()->find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }
        
        // upload gambar baru
        if ($request->hasFile('images')) {
            $currentImageCount = $problem->images()->count();
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('problems', 'public');
                $problem->images()->create([
                    'image_path' => $path,
                    'order' => $currentImageCount + $index + 1,
                ]);
            }
        }
        
        $problem->update($validated);
        
        return redirect()
            ->route('institution.problems.show', $problem->id)
            ->with('success', 'Problem berhasil diperbarui!');
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
            return back()->with('error', 'Tidak dapat menghapus problem yang sudah memiliki aplikasi diterima!');
        }
        
        // hapus semua gambar
        foreach ($problem->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        
        $problem->delete();
        
        return redirect()
            ->route('institution.problems.index')
            ->with('success', 'Problem berhasil dihapus!');
    }
}