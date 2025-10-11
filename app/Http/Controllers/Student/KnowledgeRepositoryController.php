<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Province;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        // filter by search keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // filter by kategori SDG
        if ($request->filled('category')) {
            $query->whereJsonContains('categories', $request->category);
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

        // filter by university
        if ($request->filled('university')) {
            $query->where('university_name', 'like', "%{$request->university}%");
        }

        // sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('download_count', 'desc');
                break;
            case 'most_cited':
                $query->orderBy('citation_count', 'desc');
                break;
            case 'most_viewed':
                $query->orderBy('view_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $documents = $query->paginate(12);

        // featured documents
        $featuredDocuments = Document::where('is_public', true)
            ->where('status', 'approved')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // ✅ PERBAIKAN: tambahkan total_institutions ke stats
        $stats = [
            'total_documents' => Document::where('is_public', true)
                                        ->where('status', 'approved')
                                        ->count(),
            'total_downloads' => Document::where('is_public', true)
                                        ->where('status', 'approved')
                                        ->sum('download_count'),
            'total_views' => Document::where('is_public', true)
                                    ->where('status', 'approved')
                                    ->sum('view_count'),
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

                foreach ($categories as $category) {
                    $query->orWhereJsonContains('categories', $category);
                }
            })
            ->limit(4)
            ->get();

        return view('student.repository.show', compact('document', 'relatedDocuments'));
    }

    /**
     * download dokumen dari supabase (redirect ke public URL)
     */
    public function download($id)
    {
        try {
            $document = Document::findOrFail($id);

            // cek akses
            if (!$document->is_public || $document->status !== 'approved') {
                return back()->with('error', 'Anda tidak memiliki akses ke dokumen ini');
            }

            // increment download count
            $document->increment('download_count');

            // redirect ke supabase public URL
            $publicUrl = document_url($document->file_path);
            
            return redirect($publicUrl);

        } catch (\Exception $e) {
            \Log::error('Document download error', [
                'document_id' => $id,
                'error_message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mengunduh file.');
        }
    }
}