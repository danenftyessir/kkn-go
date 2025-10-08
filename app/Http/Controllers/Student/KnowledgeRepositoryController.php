<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Province;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * controller untuk knowledge repository
 * mahasiswa bisa browse dan download dokumen hasil KKN
 * FIXED: tambah variable $stats di index
 */
class KnowledgeRepositoryController extends Controller
{
    /**
     * tampilkan halaman repository dengan list dokumen
     */
    public function index(Request $request)
    {
        $query = Document::with(['uploader.student.university', 'province', 'regency'])
                         ->where('is_public', true)
                         ->where('status', 'approved');

        // search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%")
                  ->orWhere('institution_name', 'like', "%{$search}%");
            });
        }

        // filter kategori
        if ($request->filled('category')) {
            $query->whereJsonContains('categories', $request->category);
        }

        // filter lokasi
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // filter universitas
        if ($request->filled('university')) {
            $query->where('university_name', 'like', "%{$request->university}%");
        }

        // filter tahun
        if ($request->filled('year')) {
            $query->where('year', $request->year);
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
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default: // latest
                $query->orderBy('created_at', 'desc');
                break;
        }

        $documents = $query->paginate(12);
        
        // data untuk filter
        $provinces = Province::orderBy('name')->get();
        $universities = University::orderBy('name')->get();
        $years = Document::where('is_public', true)
                        ->where('status', 'approved')
                        ->distinct()
                        ->pluck('year')
                        ->filter()
                        ->sort()
                        ->values();

        // dokumen featured
        $featuredDocuments = Document::where('is_public', true)
                                    ->where('status', 'approved')
                                    ->where('is_featured', true)
                                    ->orderBy('download_count', 'desc')
                                    ->limit(3)
                                    ->get();

        // FIXED: tambah statistik untuk view
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
            'total_universities' => Document::where('is_public', true)
                                            ->where('status', 'approved')
                                            ->distinct('university_name')
                                            ->count('university_name'),
        ];

        return view('student.repository.index', compact(
            'documents',
            'provinces',
            'universities',
            'years',
            'featuredDocuments',
            'stats' // FIXED: tambahkan variable stats
        ));
    }

    /**
     * tampilkan detail dokumen
     */
    public function show($id)
    {
        $document = Document::with(['uploader.student.university', 'province', 'regency', 'project'])
                           ->where('is_public', true)
                           ->where('status', 'approved')
                           ->findOrFail($id);

        // increment view count
        $document->increment('view_count');

        // dokumen terkait
        $relatedDocuments = Document::where('is_public', true)
                                   ->where('status', 'approved')
                                   ->where('id', '!=', $document->id)
                                   ->where(function($query) use ($document) {
                                       // cari berdasarkan kategori yang sama
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
     * download dokumen dari supabase
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

            // dapatkan file dari supabase
            $disk = config('filesystems.default');
            $filePath = $document->file_path;

            // cek apakah file ada di storage
            if (!Storage::disk($disk)->exists($filePath)) {
                \Log::error('File tidak ditemukan di storage', [
                    'document_id' => $document->id,
                    'file_path' => $filePath,
                    'disk' => $disk
                ]);
                
                abort(404, 'File tidak ditemukan. Path: ' . $filePath);
            }

            // dapatkan file content dari storage
            $fileContent = Storage::disk($disk)->get($filePath);
            
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
            return response($fileContent, 200, [
                'Content-Type' => $this->getContentType($extension),
                'Content-Disposition' => 'attachment; filename="' . $downloadFilename . '"',
                'Content-Length' => strlen($fileContent),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
            
        } catch (\Exception $e) {
            // log error untuk debugging
            \Log::error('Document download error: ' . $e->getMessage(), [
                'document_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mengunduh file: ' . $e->getMessage());
        }
    }

    /**
     * sanitize filename untuk download yang aman
     */
    protected function sanitizeFilename($title)
    {
        // hapus karakter spesial
        $filename = preg_replace('/[^A-Za-z0-9\-_]/', '-', $title);
        
        // hapus multiple dash
        $filename = preg_replace('/-+/', '-', $filename);
        
        // trim dash di awal dan akhir
        $filename = trim($filename, '-');
        
        // batasi panjang
        $filename = Str::limit($filename, 100, '');
        
        return $filename;
    }

    /**
     * dapatkan content type berdasarkan extension
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
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * generate citation (APA, MLA, IEEE)
     */
    public function generateCitation(Request $request, $id)
    {
        $document = Document::with(['uploader.student'])->findOrFail($id);
        $style = $request->get('style', 'apa');

        $citation = '';
        $authorName = $document->author_name ?? $document->uploader->name;
        $year = $document->year ?? date('Y');
        $title = $document->title;

        switch ($style) {
            case 'mla':
                $citation = "{$authorName}. \"{$title}.\" {$year}.";
                break;
            case 'ieee':
                $citation = "{$authorName}, \"{$title},\" {$year}.";
                break;
            default: // apa
                $citation = "{$authorName} ({$year}). {$title}.";
                break;
        }

        return response()->json([
            'success' => true,
            'citation' => $citation,
            'style' => $style,
        ]);
    }

    /**
     * bookmark dokumen
     */
    public function bookmark($id)
    {
        // TODO: implementasi bookmark functionality
        return response()->json([
            'success' => true,
            'message' => 'Fitur bookmark sedang dalam pengembangan',
        ]);
    }
}