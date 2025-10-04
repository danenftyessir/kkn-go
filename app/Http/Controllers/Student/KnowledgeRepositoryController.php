<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Province;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * controller untuk knowledge repository
 * mengelola dokumen hasil proyek yang dapat diakses semua user
 */
class KnowledgeRepositoryController extends Controller
{
    /**
     * tampilkan halaman knowledge repository
     */
    public function index(Request $request)
    {
        $query = Document::where('is_public', true)
                        ->where('status', 'approved')
                        ->with(['uploader', 'province', 'regency', 'project']);

        // search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        // filter by category (SDG)
        if ($request->filled('category')) {
            $query->whereJsonContains('categories', $request->category);
        }

        // filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
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
        
        $years = Document::where('is_public', true)
                        ->where('status', 'approved')
                        ->selectRaw('DISTINCT year')
                        ->whereNotNull('year')
                        ->orderBy('year', 'desc')
                        ->pluck('year');

        // featured documents
        $featuredDocuments = Document::where('is_public', true)
                                    ->where('status', 'approved')
                                    ->where('is_featured', true)
                                    ->limit(3)
                                    ->get();

        // statistics - DIPERBAIKI: tambahkan total_institutions
        $stats = [
            'total_documents' => Document::where('is_public', true)->where('status', 'approved')->count(),
            'total_downloads' => Document::where('is_public', true)->where('status', 'approved')->sum('download_count'),
            'total_views' => Document::where('is_public', true)->where('status', 'approved')->sum('view_count'),
            'total_institutions' => Document::where('is_public', true)
                                          ->where('status', 'approved')
                                          ->whereNotNull('institution_name')
                                          ->distinct('institution_name')
                                          ->count('institution_name'),
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
        $document = Document::where('is_public', true)
                           ->where('status', 'approved')
                           ->with(['uploader', 'province', 'regency', 'project.student.user'])
                           ->findOrFail($id);

        // increment view count
        $document->increment('view_count');

        // related documents berdasarkan kategori
        $relatedDocuments = Document::where('is_public', true)
                                   ->where('status', 'approved')
                                   ->where('id', '!=', $document->id)
                                   ->where(function($query) use ($document) {
                                       // cari dokumen dengan kategori yang sama
                                       if ($document->categories) {
                                           $categories = is_array($document->categories) 
                                               ? $document->categories 
                                               : json_decode($document->categories, true) ?? [];
                                           
                                           foreach ($categories as $category) {
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
        try {
            $document = Document::findOrFail($id);
            
            // cek apakah dokumen public atau user punya akses
            if (!$document->is_public && $document->status !== 'approved') {
                abort(403, 'Anda tidak memiliki akses ke dokumen ini');
            }

            // increment download count
            $document->increment('download_count');

            // dapatkan path file
            $filePath = storage_path('app/public/' . $document->file_path);

            // cek apakah file ada
            if (!file_exists($filePath)) {
                // coba path alternatif tanpa 'public/'
                $altPath = storage_path('app/' . $document->file_path);
                if (file_exists($altPath)) {
                    $filePath = $altPath;
                } else {
                    abort(404, 'File tidak ditemukan. Path: ' . $document->file_path);
                }
            }

            // sanitize filename untuk download
            $safeFilename = $this->sanitizeFilename($document->title);
            
            // dapatkan extension dari file_type atau file_path
            $extension = $document->file_type;
            if (empty($extension)) {
                $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
            }
            
            // pastikan extension tidak mengandung dot
            $extension = ltrim($extension, '.');
            
            // buat nama file final
            $downloadFilename = $safeFilename . '.' . $extension;

            // return file untuk di-download dengan header yang benar
            return response()->download($filePath, $downloadFilename, [
                'Content-Type' => $this->getContentType($extension),
                'Content-Disposition' => 'attachment; filename="' . $downloadFilename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
            
        } catch (\Exception $e) {
            // log error untuk debugging
            \Log::error('Document download error: ' . $e->getMessage(), [
                'document_id' => $id,
                'file_path' => $document->file_path ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            // redirect kembali dengan error message
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh file: ' . $e->getMessage());
        }
    }

    /**
     * get citation text untuk dokumen
     */
    public function getCitation(Request $request, $id)
    {
        $document = Document::where('is_public', true)
                           ->where('status', 'approved')
                           ->findOrFail($id);
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
        $year = $document->year ?? now()->year;
        $title = $document->title;
        $publisher = $document->institution_name ?? 'KKN-GO Platform';

        switch ($style) {
            case 'mla':
                // format MLA: Author. "Title." Publisher, Year.
                return "{$author}. \"{$title}.\" {$publisher}, {$year}.";
                
            case 'ieee':
                // format IEEE: Author, "Title," Publisher, Year.
                return "{$author}, \"{$title},\" {$publisher}, {$year}.";
                
            case 'apa':
            default:
                // format APA: Author. (Year). Title. Publisher.
                return "{$author}. ({$year}). {$title}. {$publisher}.";
        }
    }

    /**
     * sanitize filename untuk download
     * hapus karakter yang tidak diperbolehkan di filename
     */
    protected function sanitizeFilename($filename)
    {
        // hapus karakter yang tidak diperbolehkan: / \ : * ? " < > |
        $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $filename);
        
        // hapus multiple spaces dan trim
        $filename = preg_replace('/\s+/', ' ', $filename);
        $filename = trim($filename);
        
        // hapus karakter non-ASCII yang bisa bermasalah
        $filename = preg_replace('/[^\x20-\x7E]/', '', $filename);
        
        // batasi panjang filename (max 200 karakter)
        if (strlen($filename) > 200) {
            $filename = substr($filename, 0, 200);
        }
        
        // jika filename kosong setelah sanitasi, gunakan default
        if (empty($filename)) {
            $filename = 'document-' . time();
        }
        
        return $filename;
    }

    /**
     * get content type berdasarkan extension
     */
    protected function getContentType($extension)
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt' => 'text/plain',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }
}