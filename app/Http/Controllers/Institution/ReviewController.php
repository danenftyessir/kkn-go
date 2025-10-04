<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Project;
use App\Services\ReviewService;

/**
 * controller untuk manajemen review mahasiswa oleh instansi
 */
class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * tampilkan daftar review yang telah diberikan
     */
    public function index(Request $request)
    {
        $institution = auth()->user()->institution;

        $query = Review::with([
            'student.user',
            'student.university',
            'project.problem'
        ])
        ->where('reviewer_type', 'institution')
        ->whereHas('project', function($q) use ($institution) {
            $q->where('institution_id', $institution->id);
        });

        // filter berdasarkan rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // search berdasarkan nama mahasiswa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $query->orderBy('rating', 'asc');
                break;
            default:
                $query->latest();
        }

        $reviews = $query->paginate(15)->withQueryString();

        // statistik review
        $stats = [
            'total' => Review::where('reviewer_type', 'institution')
                ->whereHas('project', function($q) use ($institution) {
                    $q->where('institution_id', $institution->id);
                })->count(),
            'average_rating' => Review::where('reviewer_type', 'institution')
                ->whereHas('project', function($q) use ($institution) {
                    $q->where('institution_id', $institution->id);
                })->avg('rating') ?? 0,
            'five_star' => Review::where('reviewer_type', 'institution')
                ->whereHas('project', function($q) use ($institution) {
                    $q->where('institution_id', $institution->id);
                })->where('rating', 5)->count(),
            'four_star' => Review::where('reviewer_type', 'institution')
                ->whereHas('project', function($q) use ($institution) {
                    $q->where('institution_id', $institution->id);
                })->where('rating', 4)->count(),
        ];

        return view('institution.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * tampilkan form create review untuk proyek yang selesai
     */
    public function create($projectId)
    {
        $institution = auth()->user()->institution;

        $project = Project::with([
            'student.user',
            'student.university',
            'problem'
        ])
        ->where('institution_id', $institution->id)
        ->findOrFail($projectId);

        // validasi: hanya bisa review jika proyek sudah selesai
        if ($project->status !== 'completed') {
            return redirect()->route('institution.projects.show', $project->id)
                           ->with('error', 'Hanya dapat memberikan review untuk proyek yang sudah selesai.');
        }

        // cek apakah sudah pernah review
        if ($project->rating) {
            return redirect()->route('institution.projects.show', $project->id)
                           ->with('error', 'Anda sudah memberikan review untuk proyek ini.');
        }

        return view('institution.reviews.create', compact('project'));
    }

    /**
     * simpan review baru
     */
    public function store(Request $request, $projectId)
    {
        $institution = auth()->user()->institution;

        $project = Project::where('institution_id', $institution->id)->findOrFail($projectId);

        // validasi
        if ($project->status !== 'completed') {
            return back()->with('error', 'Hanya dapat memberikan review untuk proyek yang sudah selesai.');
        }

        if ($project->rating) {
            return back()->with('error', 'Anda sudah memberikan review untuk proyek ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'strengths' => 'nullable|string|max:500',
            'improvements' => 'nullable|string|max:500',
            'would_collaborate_again' => 'boolean',
        ]);

        try {
            // gunakan service untuk handle logic
            $review = $this->reviewService->createInstitutionReview(
                $project,
                $validated['rating'],
                $validated['review'],
                $validated['strengths'] ?? null,
                $validated['improvements'] ?? null,
                $validated['would_collaborate_again'] ?? false
            );

            return redirect()->route('institution.projects.show', $project->id)
                           ->with('success', 'Review berhasil diberikan!');

        } catch (\Exception $e) {
            return back()->withInput()
                       ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * tampilkan detail review
     */
    public function show($id)
    {
        $institution = auth()->user()->institution;

        $review = Review::with([
            'student.user',
            'student.university',
            'project.problem'
        ])
        ->where('reviewer_type', 'institution')
        ->whereHas('project', function($q) use ($institution) {
            $q->where('institution_id', $institution->id);
        })
        ->findOrFail($id);

        return view('institution.reviews.show', compact('review'));
    }

    /**
     * edit review yang sudah diberikan
     */
    public function edit($id)
    {
        $institution = auth()->user()->institution;

        $review = Review::with([
            'student.user',
            'student.university',
            'project.problem'
        ])
        ->where('reviewer_type', 'institution')
        ->whereHas('project', function($q) use ($institution) {
            $q->where('institution_id', $institution->id);
        })
        ->findOrFail($id);

        // hanya bisa edit dalam 30 hari
        if ($review->created_at->addDays(30)->isPast()) {
            return redirect()->route('institution.reviews.show', $review->id)
                           ->with('error', 'Review tidak dapat diedit setelah 30 hari.');
        }

        return view('institution.reviews.edit', compact('review'));
    }

    /**
     * update review yang sudah diberikan
     */
    public function update(Request $request, $id)
    {
        $institution = auth()->user()->institution;

        $review = Review::where('reviewer_type', 'institution')
            ->whereHas('project', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })
            ->findOrFail($id);

        // hanya bisa edit dalam 30 hari
        if ($review->created_at->addDays(30)->isPast()) {
            return back()->with('error', 'Review tidak dapat diedit setelah 30 hari.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'strengths' => 'nullable|string|max:500',
            'improvements' => 'nullable|string|max:500',
            'would_collaborate_again' => 'boolean',
        ]);

        try {
            // gunakan service untuk handle logic
            $this->reviewService->updateReview(
                $review,
                $validated['rating'],
                $validated['review'],
                $validated['strengths'] ?? null,
                $validated['improvements'] ?? null,
                $validated['would_collaborate_again'] ?? false
            );

            return redirect()->route('institution.reviews.show', $review->id)
                           ->with('success', 'Review berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withInput()
                       ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * hapus review
     */
    public function destroy($id)
    {
        $institution = auth()->user()->institution;

        $review = Review::where('reviewer_type', 'institution')
            ->whereHas('project', function($q) use ($institution) {
                $q->where('institution_id', $institution->id);
            })
            ->findOrFail($id);

        // hanya bisa hapus dalam 7 hari
        if ($review->created_at->addDays(7)->isPast()) {
            return back()->with('error', 'Review tidak dapat dihapus setelah 7 hari.');
        }

        try {
            $this->reviewService->deleteReview($review);

            return redirect()->route('institution.reviews.index')
                           ->with('success', 'Review berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}