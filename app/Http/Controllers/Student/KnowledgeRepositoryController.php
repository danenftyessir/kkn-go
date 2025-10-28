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

        // filter by year
        if ($request->filled('year')) {
            $query->whereYear('published_at', $request->year);
        }

        // filter by institution type
        if ($request->filled('institution_type')) {
            $query->where('institution_type', $request->institution_type);
        }

        // sorting
        $sortBy = $request->get('sort', 'latest');
        
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('download_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }

        // paginate
        $documents = $query->paginate(12)->withQueryString();

        // statistik untuk featured section
        $featuredDocuments = Document::featured()
            ->with(['uploader.student', 'province'])
            ->limit(4)
            ->get();

        // data untuk filter dropdowns
        $provinces = Province::orderBy('name')->get(['id', 'name']);
        
        // years dari dokumen yang ada
        $years = Document::where('is_public', true)
            ->where('status', 'approved')
            ->whereNotNull('published_at')
            ->selectRaw('DISTINCT EXTRACT(YEAR FROM published_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        // institution types yang unik
        $institutionTypes = Document::where('is_public', true)
            ->where('status', 'approved')
            ->whereNotNull('institution_type')
            ->distinct()
            ->pluck('institution_type')
            ->filter();

        // statistik umum
        $stats = [
            'total_documents' => Document::published()->count(),
            'total_downloads' => Document::published()->sum('download_count'),
            'total_views' => Document::published()->sum('view_count'),
        ];

        return view('student.repository.index', compact(
            'documents',
            'featuredDocuments',
            'provinces',
            'years',
            'institutionTypes',
            'stats'
        ));
    }

    /**
     * tampilkan detail dokumen
     */
    public function show($id)
    {
        $document = Document::with([
            'uploader.student',
            'province',
            'regency'
        ])->findOrFail($id);

        // pastikan dokumen public dan approved
        if (!$document->is_public || $document->status !== 'approved') {
            abort(404);
        }

        // increment view count
        $document->incrementViews();

        // related documents berdasarkan kategori SDG
        $relatedDocuments = Document::published()
            ->where('id', '!=', $document->id)
            ->where(function($query) use ($document) {
                if ($document->categories && is_array($document->categories)) {
                    foreach ($document->categories as $category) {
                        $query->orWhereJsonContains('categories', $category);
                    }
                }
            })
            ->with(['uploader.student', 'province'])
            ->limit(4)
            ->get();

        return view('student.repository.show', compact(
            'document',
            'relatedDocuments'
        ));
    }

    /**
     * download dokumen
     */
    public function download($id)
    {
        $document = Document::findOrFail($id);

        // pastikan dokumen public dan approved
        if (!$document->is_public || $document->status !== 'approved') {
            abort(404);
        }

        // increment download count
        $document->incrementDownloads();

        // redirect ke URL supabase untuk download
        return redirect()->away(document_url($document->file_path));
    }
}