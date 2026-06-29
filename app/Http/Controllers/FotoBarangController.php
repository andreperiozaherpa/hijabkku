<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\FotoBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('barang.foto');
    }

    /**
     * Get Yajra DataTables JSON data.
     */
    public function data()
    {
        $data = DataBarang::with('fotos.user')->orderBy('nama_barang');

        return DataTables()->of($data)
            ->addColumn('foto_utama', function ($product) {
                if ($product->foto) {
                    return '<img src="/'.$product->foto.'" class="photo-thumbnail main-table-preview-img" data-path="'.$product->foto.'" alt="Foto Utama">';
                }

                return '<div class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded" style="width: 55px; height: 55px;"><i data-acorn-icon="picture" data-acorn-size="20"></i></div>';
            })
            ->addColumn('total_foto', function ($product) {
                $totalCount = $product->fotos->count();
                $pendingCount = $product->fotos->where('is_verified', false)->count();

                $badge = '<span class="badge bg-outline-primary px-2.5 py-1.5 photo-count-badge-row-'.$product->id.'">'.$totalCount.' Foto</span>';
                if ($pendingCount > 0) {
                    $badge .= ' <span class="badge bg-warning ms-1 px-2 py-1 unverified-badge-row-'.$product->id.'">'.$pendingCount.' Pending</span>';
                }

                return $badge;
            })
            ->addColumn('aksi', function ($product) {
                return '<button class="btn btn-sm btn-icon btn-icon-start btn-outline-primary btn-manage-photos me-1" data-id="'.$product->id.'" data-name="'.$product->nama_barang.'" data-kode="'.$product->kode.'"><i data-acorn-icon="upload-cloud" data-acorn-size="13"></i> Kelola Foto</button>'.
                       '<button class="btn btn-sm btn-icon btn-icon-start btn-outline-secondary btn-manage-desc" data-id="'.$product->id.'" data-name="'.$product->nama_barang.'" data-kode="'.$product->kode.'"><i data-acorn-icon="file-text" data-acorn-size="13"></i> Kelola Deskripsi</button>';
            })
            ->rawColumns(['foto_utama', 'total_foto', 'aksi'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = DataBarang::with(['fotos' => function ($query) {
            $query->with('user')->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        return response()->json([
            'product' => $product,
            'fotos' => $product->fotos,
        ]);
    }

    /**
     * Upload photos for the specified product.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'data_barang_id' => 'required|exists:data_barangs,id',
            'file' => 'required|image|max:5120', // max 5MB
        ]);

        $productId = $request->data_barang_id;
        $file = $request->file('file');

        $path = $file->store('uploads/produk', 'public');
        $fullPath = 'storage/'.$path;

        $isAdmin = auth()->user() && auth()->user()->role === 'admin';

        $foto = new FotoBarang;
        $foto->data_barang_id = $productId;
        $foto->path = $fullPath;
        $foto->is_verified = $isAdmin;
        $foto->user_id = auth()->id();

        $hasVerifiedPhotos = FotoBarang::where('data_barang_id', $productId)->where('is_verified', true)->exists();

        if ($foto->is_verified && ! $hasVerifiedPhotos) {
            $foto->is_main = true;
            DataBarang::where('id', $productId)->update(['foto' => $fullPath]);
        }

        $foto->save();

        return response()->json([
            'success' => true,
            'foto' => $foto->load('user'),
        ]);
    }

    /**
     * Verify a product photo (Admin only).
     */
    public function verify(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:foto_barangs,id',
        ]);

        if (! auth()->user() || auth()->user()->role !== 'admin') {
            return response()->json([
                'icon' => 'error',
                'title' => 'Ditolak',
                'text' => 'Hanya admin yang dapat memverifikasi foto.',
            ], 403);
        }

        $foto = FotoBarang::findOrFail($request->id);
        $productId = $foto->data_barang_id;

        $foto->is_verified = true;

        $hasOtherVerified = FotoBarang::where('data_barang_id', $productId)
            ->where('is_verified', true)
            ->where('id', '!=', $foto->id)
            ->exists();

        if (! $hasOtherVerified) {
            $foto->is_main = true;
            DataBarang::where('id', $productId)->update(['foto' => $foto->path]);
        }

        $foto->save();

        return response()->json([
            'icon' => 'success',
            'title' => 'Sukses',
            'text' => 'Foto berhasil diverifikasi.',
        ]);
    }

    /**
     * Set a photo as the main photo for the product.
     */
    public function setMain(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:foto_barangs,id',
        ]);

        $foto = FotoBarang::findOrFail($request->id);
        $productId = $foto->data_barang_id;

        if (! $foto->is_verified) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Ditolak',
                'text' => 'Foto harus diverifikasi terlebih dahulu sebelum dijadikan foto utama.',
            ], 422);
        }

        // Reset main flags
        FotoBarang::where('data_barang_id', $productId)->update(['is_main' => false]);

        // Set as main
        $foto->is_main = true;
        $foto->save();

        // Update product table main photo
        DataBarang::where('id', $productId)->update(['foto' => $foto->path]);

        return response()->json([
            'icon' => 'success',
            'title' => 'Sukses',
            'text' => 'Foto utama berhasil diperbarui.',
        ]);
    }

    /**
     * Delete a product photo.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:foto_barangs,id',
        ]);

        $foto = FotoBarang::findOrFail($request->id);
        $productId = $foto->data_barang_id;
        $wasMain = $foto->is_main;

        // Delete physical file
        $relativePath = str_replace('storage/', '', $foto->path);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }

        $foto->delete();

        if ($wasMain) {
            // Find another verified photo to set as main
            $nextPhoto = FotoBarang::where('data_barang_id', $productId)
                ->where('is_verified', true)
                ->first();
            if ($nextPhoto) {
                $nextPhoto->is_main = true;
                $nextPhoto->save();

                DataBarang::where('id', $productId)->update(['foto' => $nextPhoto->path]);
            } else {
                DataBarang::where('id', $productId)->update(['foto' => null]);
            }
        }

        return response()->json([
            'icon' => 'success',
            'title' => 'Sukses',
            'text' => 'Foto berhasil dihapus.',
        ]);
    }

    /**
     * Update description for the specified product.
     */
    public function updateDescription(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:data_barangs,id',
            'deskripsi' => 'nullable|string',
        ]);

        $product = DataBarang::findOrFail($request->id);
        $product->deskripsi = $request->deskripsi;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Deskripsi produk berhasil diperbarui.',
        ]);
    }
}
