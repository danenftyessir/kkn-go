<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Province;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

/**
 * controller untuk knowledge repository
 * mahasiswa bisa browse dan download dokumen hasil proyek
 */
class KnowledgeRepositoryController extends Controller
{
    /**
     * tampilkan halaman repository dengan filter dan statistik
     */
    public function index(Request $request)
    {
        $query = Document::with(['uploader.student', 'province', 'regency'])
            ->where('is_public', true)
            ->where('status', 'approved');

        // filter by search keyword - case insensitive dengan ILIKE
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // gunakan ILIKE untuk case insensitive di PostgreSQL
                $q->where('title', 'ILIKE', "%{$search}%")
                    ->orWhere('description', 'ILIKE', "%{$search}%")
                    ->orWhere('author_name', 'ILIKE', "%{$search}%")
                    ->orWhere('tags', 'ILIKE', "%{$search}%");
            });
        }

        // filter by kategori SDG - gunakan integer value
        if ($request->filled('category')) {
            $category = (int) $request->category;
            $query->whereJsonContains('categories', $category);
        }

        // filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // filter by regency
        if ($request->filled('regency_id')) {
            $query->where('regency_id', $request->regency_id);
        }

        // filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // sorting
        switch ($request->sort) {
            case 'popular':
                $query->orderBy('download_count', 'desc');
                break;
            case 'most_viewed':
                $query->orderBy('view_count', 'desc');
                break;
            case 'most_cited':
                $query->orderBy('citation_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // pagination
        $documents = $query->paginate(12)->withQueryString();

        // ambil featured documents untuk carousel
        $featuredDocuments = Document::with(['uploader.student', 'province'])
            ->where('is_public', true)
            ->where('status', 'approved')
            ->where('is_featured', true)
            ->latest()
            ->limit(5)
            ->get();

        // statistik untuk hero section
        $stats = [
            'total_documents' => Document::where('is_public', true)
                                         ->where('status', 'approved')
                                         ->count(),
            'total_downloads' => Document::where('is_public', true)
                                         ->where('status', 'approved')
                                         ->sum('download_count'),
            'total_institutions' => Document::where('is_public', true)
                                           ->where('status', 'approved')
                                           ->distinct('institution_name')
                                           ->count('institution_name'),
        ];

        // data untuk filters
        $provinces = Province::orderBy('name')->get();
        $years = Document::where('is_public', true)
                        ->where('status', 'approved')
                        ->whereNotNull('year')
                        ->distinct()
                        ->pluck('year')
                        ->sort()
                        ->reverse()
                        ->values();

        return view('student.repository.index', compact(
            'documents',
            'featuredDocuments',
            'stats',
            'provinces',
            'years'
        ));
    }

    /**
     * tampilkan detail dokumen
     */
    public function show($id)
    {
        $document = Document::with(['uploader.student', 'province', 'regency', 'project'])
            ->where('is_public', true)
            ->where('status', 'approved')
            ->findOrFail($id);

        // increment view count
        $document->increment('view_count');

        // ambil dokumen terkait berdasarkan kategori
        $relatedDocuments = Document::with(['uploader.student', 'province'])
            ->where('is_public', true)
            ->where('status', 'approved')
            ->where('id', '!=', $document->id)
            ->where(function ($query) use ($document) {
                $categories = is_array($document->categories)
                    ? $document->categories
                    : json_decode($document->categories, true) ?? [];
                
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $query->orWhereJsonContains('categories', $category);
                    }
                }
            })
            ->limit(3)
            ->get();

        return view('student.repository.show', compact('document', 'relatedDocuments'));
    }

    /**
     * download dokumen
     */
    public function download($id)
    {
        $document = Document::where('is_public', true)
            ->where('status', 'approved')
            ->findOrFail($id);

        // increment download count
        $document->increment('download_count');

        // redirect ke storage file
        return Storage::disk('public')->download($document->file_path, $document->title . '.pdf');
    }
}