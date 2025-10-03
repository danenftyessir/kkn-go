<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Province;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * controller untuk knowledge repository
 * mengelola dokumen hasil proyek yang dapat diakses semua user
 * 
 * path: app/Http/Controllers/Student/KnowledgeRepositoryController.php
 */
class KnowledgeRepositoryController extends Controller
{
    /**
     * tampilkan halaman knowledge repository
     */
    public function index(Request $request)
    {
        $query = Document::public()
                        ->with(['uploader', 'province', 'regency', 'project']);

        // search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // filter by category (SDG)
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // filter by year
        if ($request->filled('year')) {
            $query->byYear($request->year);
        }

        // filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // filter by university
        if ($request->filled('university')) {
            $query->where('university_name', 'like', '%' . $request->university . '%');
        }

        // sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('download_count', 'desc');
                break;
            case 'most_viewed':
                $query->orderBy('view_count', 'desc');
                break;
            case 'most_cited':
                $query->orderBy('citation_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $documents = $query->paginate(12);

        // data untuk filter
        $provinces = Province::orderBy('name')->get();
        $years = Document::public()
                        ->selectRaw('DISTINCT year')
                        ->whereNotNull('year')
                        ->orderBy('year', 'desc')
                        ->pluck('year');

        // featured documents
        $featuredDocuments = Document::public()
                                    ->featured()
                                    ->limit(3)
                                    ->get();

        // statistics
        $stats = [
            'total_documents' => Document::public()->count(),
            'total_downloads' => Document::public()->sum('download_count'),
            'total_institutions' => Document::public()->distinct('institution_name')->count(),
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
        $document = Document::public()
                           ->with(['uploader', 'province', 'regency', 'project.student.user'])
                           ->findOrFail($id);

        // increment view count
        $document->incrementViews();

        // related documents
        $relatedDocuments = Document::public()
                                   ->where('id', '!=', $document->id)
                                   ->where(function($query) use ($document) {
                                       // cari dokumen dengan kategori yang sama
                                       if ($document->categories) {
                                           foreach ($document->categories as $category) {
                                               $query->orWhereJsonContains('categories', $category);
                                           }
                                       }
                                   })
                                   ->limit(4)
                                   ->get();

        return view('student.repository.show', compact('document', 'relatedDocuments'));
    }

    /**
     * download dokumen
     */
    public function download($id)
    {
        $document = Document::public()->findOrFail($id);

        // increment download count
        $document->incrementDownloads();

        $filePath = storage_path('app/public/' . $document->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download($filePath, $document->title . '.' . $document->file_type);
    }

    /**
     * get citation text untuk dokumen
     */
    public function getCitation(Request $request, $id)
    {
        $document = Document::public()->findOrFail($id);
        $style = $request->get('style', 'apa'); // apa, mla, ieee

        $citation = $this->generateCitation($document, $style);

        return response()->json([
            'success' => true,
            'citation' => $citation,
            'style' => $style,
        ]);
    }

    /**
     * generate citation berdasarkan style
     */
    protected function generateCitation($document, $style)
    {
        $author = $document->author_name ?? 'Unknown Author';
        $year = $document->year ?? date('Y');
        $title = $document->title;
        $institution = $document->institution_name ?? '';
        $university = $document->university_name ?? '';

        switch ($style) {
            case 'apa':
                // APA: Author, A. A. (Year). Title. Institution.
                return "{$author}. ({$year}). {$title}. {$institution}, {$university}.";
                
            case 'mla':
                // MLA: Author. "Title." Institution, Year.
                return "{$author}. \"{$title}.\" {$institution}, {$year}.";
                
            case 'ieee':
                // IEEE: [1] A. Author, "Title," Institution, Year.
                return "{$author}, \"{$title},\" {$institution}, {$year}.";
                
            default:
                return "{$author}. ({$year}). {$title}. {$institution}.";
        }
    }

    /**
     * bookmark dokumen
     * TODO: implement bookmark functionality
     */
    public function bookmark($id)
    {
        // TODO: create bookmark table and implement bookmark feature
        return response()->json([
            'success' => true,
            'message' => 'Fitur bookmark sedang dalam pengembangan',
        ]);
    }

    /**
     * report dokumen
     * TODO: implement report functionality untuk dokumen yang tidak sesuai
     */
    public function report(Request $request, $id)
    {
        // TODO: implement report system
        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas laporan Anda. Tim kami akan meninjau dokumen ini.',
        ]);
    }
}