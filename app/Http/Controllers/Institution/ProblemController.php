<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\ProblemImage;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * controller untuk handle problem CRUD oleh instansi
 * dengan upload gambar ke supabase storage
 */
class ProblemController extends Controller
{
    protected $supabaseStorage;

    public function __construct()
    {
        $this->supabaseStorage = new SupabaseStorageService();
    }

    /**
     * store problem baru dengan upload gambar ke supabase
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // ... validasi lainnya
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB per image
        ]);

        try {
            DB::beginTransaction();

            // buat problem
            $problem = Problem::create([
                'institution_id' => Auth::user()->institution->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                // ... field lainnya
            ]);

            // upload gambar ke supabase (jika ada)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    // generate unique filename
                    $filename = Str::slug($problem->title) . '-' . time() . '-' . $index . '.' . $image->extension();
                    $path = 'problems/' . $filename;

                    // upload ke supabase
                    $uploadedPath = $this->supabaseStorage->uploadFile($image, $path);

                    if ($uploadedPath) {
                        // simpan ke database
                        ProblemImage::create([
                            'problem_id' => $problem->id,
                            'image_path' => $uploadedPath,
                            'caption' => $request->input("captions.{$index}"),
                            'is_cover' => $index === 0, // gambar pertama jadi cover
                            'order' => $index,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('institution.problems.show', $problem->id)
                           ->with('success', 'Problem berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                       ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * update problem dan upload gambar baru
     */
    public function update(Request $request, Problem $problem)
    {
        // validasi ownership
        if ($problem->institution_id !== Auth::user()->institution->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // ... validasi lainnya
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'delete_images' => 'nullable|array', // IDs gambar yang dihapus
        ]);

        try {
            DB::beginTransaction();

            // update problem
            $problem->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                // ... field lainnya
            ]);

            // hapus gambar yang dipilih
            if ($request->filled('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $image = ProblemImage::find($imageId);
                    if ($image && $image->problem_id == $problem->id) {
                        // delete dari supabase
                        $this->supabaseStorage->deleteFile($image->image_path);
                        
                        // delete dari database
                        $image->delete();
                    }
                }
            }

            // upload gambar baru (jika ada)
            if ($request->hasFile('images')) {
                $currentOrder = ProblemImage::where('problem_id', $problem->id)->max('order') ?? -1;
                
                foreach ($request->file('images') as $index => $image) {
                    $filename = Str::slug($problem->title) . '-' . time() . '-' . $index . '.' . $image->extension();
                    $path = 'problems/' . $filename;

                    // upload ke supabase
                    $uploadedPath = $this->supabaseStorage->uploadFile($image, $path);

                    if ($uploadedPath) {
                        ProblemImage::create([
                            'problem_id' => $problem->id,
                            'image_path' => $uploadedPath,
                            'caption' => $request->input("captions.{$index}"),
                            'is_cover' => false,
                            'order' => $currentOrder + $index + 1,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('institution.problems.show', $problem->id)
                           ->with('success', 'Problem berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                       ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * delete problem dan semua gambarnya
     */
    public function destroy(Problem $problem)
    {
        // validasi ownership
        if ($problem->institution_id !== Auth::user()->institution->id) {
            abort(403);
        }

        // validasi: tidak bisa hapus jika sudah ada applications
        if ($problem->applications()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus masalah yang sudah memiliki aplikasi.');
        }

        try {
            DB::beginTransaction();

            // hapus semua gambar dari supabase
            foreach ($problem->images as $image) {
                $this->supabaseStorage->deleteFile($image->image_path);
                $image->delete();
            }

            // hapus problem
            $problem->delete();

            DB::commit();

            return redirect()->route('institution.problems.index')
                           ->with('success', 'Problem berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}