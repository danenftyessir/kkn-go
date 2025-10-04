<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Province;
use App\Models\Regency;
use App\Models\ProblemImage;
use App\Services\ProblemService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProblemController extends Controller
{
    protected $problemService;

    public function __construct(ProblemService $problemService)
    {
        $this->problemService = $problemService;
    }

    /**
     * tampilkan daftar masalah milik instansi
     */
    public function index(Request $request)
    {
        $institution = auth()->user()->institution;

        $query = Problem::where('institution_id', $institution->id)
                       ->with(['province', 'regency', 'images']);

        // filter berdasarkan status
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
            case 'most_applied':
                $query->orderBy('applications_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $problems = $query->paginate(10)->withQueryString();

        // statistik
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
     * tampilkan form create masalah baru
     */
    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        
        return view('institution.problems.create', compact('provinces'));
    }

    /**
     * simpan masalah baru ke database
     */
    public function store(Request $request)
    {
        $institution = auth()->user()->institution;

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
            'sdg_categories' => 'required|array',
            'required_students' => 'required|integer|min:1',
            'required_skills' => 'nullable|string',
            'required_majors' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'application_deadline' => 'required|date|before:start_date',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'expected_outcomes' => 'nullable|string',
            'deliverables' => 'nullable|string',
            'facilities_provided' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // hitung durasi
            $startDate = new \DateTime($validated['start_date']);
            $endDate = new \DateTime($validated['end_date']);
            $durationMonths = $startDate->diff($endDate)->m + ($startDate->diff($endDate)->y * 12);

            // convert skills dan majors ke array
            $requiredSkills = $validated['required_skills'] ? array_map('trim', explode(',', $validated['required_skills'])) : [];
            $requiredMajors = $validated['required_majors'] ? array_map('trim', explode(',', $validated['required_majors'])) : [];
            $deliverables = $validated['deliverables'] ? array_map('trim', explode(',', $validated['deliverables'])) : [];
            $facilities = $validated['facilities_provided'] ? array_map('trim', explode(',', $validated['facilities_provided'])) : [];

            // buat problem
            $problem = Problem::create([
                'institution_id' => $institution->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'background' => $validated['background'] ?? null,
                'objectives' => $validated['objectives'] ?? null,
                'scope' => $validated['scope'] ?? null,
                'province_id' => $validated['province_id'],
                'regency_id' => $validated['regency_id'],
                'village' => $validated['village'] ?? null,
                'detailed_location' => $validated['detailed_location'] ?? null,
                'sdg_categories' => $validated['sdg_categories'],
                'required_students' => $validated['required_students'],
                'required_skills' => $requiredSkills,
                'required_majors' => !empty($requiredMajors) ? $requiredMajors : null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'application_deadline' => $validated['application_deadline'],
                'duration_months' => $durationMonths,
                'difficulty_level' => $validated['difficulty_level'],
                'expected_outcomes' => $validated['expected_outcomes'] ?? null,
                'deliverables' => !empty($deliverables) ? $deliverables : null,
                'facilities_provided' => !empty($facilities) ? $facilities : null,
                'status' => 'draft',
            ]);

            // upload gambar jika ada
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('problems', 'public');
                    
                    ProblemImage::create([
                        'problem_id' => $problem->id,
                        'image_path' => $path,
                        'order' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('institution.problems.show', $problem->id)
                           ->with('success', 'Masalah berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                       ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * tampilkan detail masalah
     */
    public function show($id)
    {
        $institution = auth()->user()->institution;
        
        $problem = Problem::with([
            'images',
            'province',
            'regency',
            'applications.student.user',
            'applications.student.university'
        ])
        ->where('institution_id', $institution->id)
        ->findOrFail($id);

        // statistik aplikasi
        $applicationStats = [
            'total' => $problem->applications()->count(),
            'pending' => $problem->applications()->where('status', 'pending')->count(),
            'under_review' => $problem->applications()->where('status', 'under_review')->count(),
            'accepted' => $problem->applications()->where('status', 'accepted')->count(),
            'rejected' => $problem->applications()->where('status', 'rejected')->count(),
        ];

        return view('institution.problems.show', compact('problem', 'applicationStats'));
    }

    /**
     * tampilkan form edit masalah
     */
    public function edit($id)
    {
        $institution = auth()->user()->institution;
        
        $problem = Problem::with(['images', 'province', 'regency'])
                         ->where('institution_id', $institution->id)
                         ->findOrFail($id);

        // tidak bisa edit jika sudah ada aplikasi yang diterima
        if ($problem->applications()->where('status', 'accepted')->exists()) {
            return redirect()->route('institution.problems.show', $problem->id)
                           ->with('error', 'Tidak dapat mengedit masalah yang sudah memiliki aplikasi yang diterima.');
        }

        $provinces = Province::orderBy('name')->get();
        $regencies = Regency::where('province_id', $problem->province_id)->orderBy('name')->get();

        return view('institution.problems.edit', compact('problem', 'provinces', 'regencies'));
    }

    /**
     * update masalah di database
     */
    public function update(Request $request, $id)
    {
        $institution = auth()->user()->institution;
        
        $problem = Problem::where('institution_id', $institution->id)->findOrFail($id);

        // tidak bisa edit jika sudah ada aplikasi yang diterima
        if ($problem->applications()->where('status', 'accepted')->exists()) {
            return back()->with('error', 'Tidak dapat mengedit masalah yang sudah memiliki aplikasi yang diterima.');
        }

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
            'sdg_categories' => 'required|array',
            'required_students' => 'required|integer|min:1',
            'required_skills' => 'nullable|string',
            'required_majors' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'application_deadline' => 'required|date|before:start_date',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'expected_outcomes' => 'nullable|string',
            'deliverables' => 'nullable|string',
            'facilities_provided' => 'nullable|string',
            'delete_images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'status' => 'required|in:draft,open,closed',
        ]);

        try {
            DB::beginTransaction();

            // hitung durasi
            $startDate = new \DateTime($validated['start_date']);
            $endDate = new \DateTime($validated['end_date']);
            $durationMonths = $startDate->diff($endDate)->m + ($startDate->diff($endDate)->y * 12);

            // convert ke array
            $requiredSkills = $validated['required_skills'] ? array_map('trim', explode(',', $validated['required_skills'])) : [];
            $requiredMajors = $validated['required_majors'] ? array_map('trim', explode(',', $validated['required_majors'])) : [];
            $deliverables = $validated['deliverables'] ? array_map('trim', explode(',', $validated['deliverables'])) : [];
            $facilities = $validated['facilities_provided'] ? array_map('trim', explode(',', $validated['facilities_provided'])) : [];

            // update problem
            $problem->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'background' => $validated['background'] ?? null,
                'objectives' => $validated['objectives'] ?? null,
                'scope' => $validated['scope'] ?? null,
                'province_id' => $validated['province_id'],
                'regency_id' => $validated['regency_id'],
                'village' => $validated['village'] ?? null,
                'detailed_location' => $validated['detailed_location'] ?? null,
                'sdg_categories' => $validated['sdg_categories'],
                'required_students' => $validated['required_students'],
                'required_skills' => $requiredSkills,
                'required_majors' => !empty($requiredMajors) ? $requiredMajors : null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'application_deadline' => $validated['application_deadline'],
                'duration_months' => $durationMonths,
                'difficulty_level' => $validated['difficulty_level'],
                'expected_outcomes' => $validated['expected_outcomes'] ?? null,
                'deliverables' => !empty($deliverables) ? $deliverables : null,
                'facilities_provided' => !empty($facilities) ? $facilities : null,
                'status' => $validated['status'],
            ]);

            // hapus gambar yang dipilih
            if ($request->filled('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $image = ProblemImage::find($imageId);
                    if ($image && $image->problem_id == $problem->id) {
                        Storage::disk('public')->delete($image->image_path);
                        $image->delete();
                    }
                }
            }

            // upload gambar baru jika ada
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('problems', 'public');
                    
                    ProblemImage::create([
                        'problem_id' => $problem->id,
                        'image_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('institution.problems.show', $problem->id)
                           ->with('success', 'Masalah berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                       ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * hapus masalah dari database
     */
    public function destroy($id)
    {
        $institution = auth()->user()->institution;
        
        $problem = Problem::where('institution_id', $institution->id)->findOrFail($id);

        // tidak bisa hapus jika sudah ada aplikasi
        if ($problem->applications()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus masalah yang sudah memiliki aplikasi.');
        }

        try {
            DB::beginTransaction();

            // hapus semua gambar
            foreach ($problem->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            // hapus problem
            $problem->delete();

            DB::commit();

            return redirect()->route('institution.problems.index')
                           ->with('success', 'Masalah berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * get regencies berdasarkan province (AJAX)
     */
    public function getRegencies($provinceId)
    {
        $regencies = Regency::where('province_id', $provinceId)
                           ->orderBy('name')
                           ->get(['id', 'name']);
        
        return response()->json($regencies);
    }

    /**
     * toggle status problem (draft/open/closed)
     */
    public function toggleStatus(Request $request, $id)
    {
        $institution = auth()->user()->institution;
        
        $problem = Problem::where('institution_id', $institution->id)->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,open,closed'
        ]);

        $problem->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah!',
            'status' => $problem->status
        ]);
    }
}