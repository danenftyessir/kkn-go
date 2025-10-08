<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KnowledgeRepositoryController extends Controller
{
    /**
     * tampilkan halaman repository
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
        $featuredDocuments = Document::featured()
            ->where('is_public', true)
            ->where('status', 'approved')
            ->limit(3)
            ->get();

        return view('student.repository.index', compact('documents', 'featuredDocuments'));
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

    /**
     * sanitize filename untuk download yang aman
     */
    protected function sanitizeFilename($title)
    {
        // hapus karakter spesial dan ganti dengan dash
        $filename = preg_replace('/[^A-Za-z0-9\-_]/', '-', $title);

        // hapus multiple dash
        $filename = preg_replace('/-+/', '-', $filename);

        // trim dash di awal dan akhir
        $filename = trim($filename, '-');

        // batasi panjang
        $filename = Str::limit($filename, 100, '');

        // fallback jika kosong
        if (empty($filename)) {
            $filename = 'document-' . time();
        }

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
            'txt' => 'text/plain',
            'csv' => 'text/csv',
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
        $institution = $document->institution_name ?? 'Unknown Institution';

        switch ($style) {
            case 'mla':
                // format MLA: Author. "Title." Year.
                $citation = "{$authorName}. \"{$title}.\" {$year}. {$institution}.";
                break;

            case 'ieee':
                // format IEEE: Author, "Title," Year.
                $citation = "{$authorName}, \"{$title},\" {$institution}, {$year}.";
                break;

            case 'apa':
            default:
                // format APA: Author. (Year). Title. Institution.
                $citation = "{$authorName}. ({$year}). {$title}. {$institution}.";
                break;
        }

        return response()->json([
            'success' => true,
            'citation' => $citation,
            'style' => $style
        ]);
    }

    /**
     * bookmark dokumen (untuk fitur future)
     */
    public function bookmark($id)
    {
        // implementasi bookmark jika diperlukan
        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil di-bookmark'
        ]);
    }
}