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

        // filter by kategori SDG - gunakan integer value dan whereJsonContains
        if ($request->filled('category')) {
            $category = (int) $request->category;
            $query->whereJsonContains('categories', $category);
        }

        // filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // filter by year
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        // sorting
        $sortBy = $request->get('sort', 'latest');
        
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('download_count', 'desc');
                break;
            case 'views':
                $query->orderBy('view_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // paginate
        $documents = $query->paginate(12)->withQueryString();

        // data untuk filters
        $provinces = Province::orderBy('name')->get(['id', 'name']);
        
        // ambil unique years dari dokumen
        $years = Document::where('is_public', true)
            ->where('status', 'approved')
            ->selectRaw('DISTINCT EXTRACT(YEAR FROM created_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        // featured documents
        $featuredDocuments = Document::where('is_featured', true)
            ->where('is_public', true)
            ->where('status', 'approved')
            ->with(['uploader.student', 'province'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // statistik
        $stats = [
            'total_documents' => Document::where('is_public', true)->where('status', 'approved')->count(),
            'total_downloads' => Document::where('is_public', true)->where('status', 'approved')->sum('download_count'),
            'total_provinces' => Document::where('is_public', true)->where('status', 'approved')->distinct('province_id')->count(),
            'total_universities' => Document::where('is_public', true)->where('status', 'approved')->distinct('university_name')->count(),
        ];

        return view('student.repository.index', compact(
            'documents',
            'provinces',
            'years',
            'featuredDocuments',
            'stats'
        ));
    }

    /**
     * tampilkan detail dokumen
     */
    public function show($id)
    {
        $document = Document::with(['uploader.student', 'province', 'regency'])
            ->where('is_public', true)
            ->where('status', 'approved')
            ->findOrFail($id);

        // increment view count
        $document->increment('view_count');

        // related documents berdasarkan kategori dan lokasi
        $relatedDocuments = Document::where('id', '!=', $document->id)
            ->where('is_public', true)
            ->where('status', 'approved')
            ->where(function($query) use ($document) {
                // filter berdasarkan kategori atau provinsi yang sama
                $query->where('province_id', $document->province_id);
                
                // jika ada categories, tambahkan filter
                if (!empty($document->categories) && is_array($document->categories)) {
                    foreach ($document->categories as $category) {
                        $query->orWhereJsonContains('categories', $category);
                    }
                }
            })
            ->with(['uploader.student', 'province'])
            ->limit(6)
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

        // get file dari supabase
        $fileUrl = supabase_url($document->file_path);

        // redirect ke URL file untuk download
        return redirect($fileUrl);
    }
}