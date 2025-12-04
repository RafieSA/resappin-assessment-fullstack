<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    // 1. Fungsi Menampilkan Daftar Barang (INDEX)
    public function index()
    {
        // Ambil data terbaru dari database
        $barangs = Barang::latest()->get();
        
        // Tampilkan view 'barang.index' dan kirim data '$barangs'
        return view('barang.index', compact('barangs'));
    }

    // 2. Fungsi Menyimpan Barang Baru
    public function store(Request $request)
    {
        $request->merge([
            'harga_beli' => str_replace(['Rp ', '.', ' '], '', $request->harga_beli),
            'harga_jual' => str_replace(['Rp ', '.', ' '], '', $request->harga_jual),
        ]);

        // B. Validasi Input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0', 
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            // Pesan Error
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'harga_beli.required'  => 'Harga beli wajib diisi.',
            'harga_beli.numeric'   => 'Harga beli harus berupa angka valid.',
            'stok.required'        => 'Stok wajib diisi.',
            'gambar.image'         => 'File harus berupa gambar.',
            'gambar.max'           => 'Ukuran gambar maksimal 2MB.',
        ]);

        // C. Siapkan Data
        $data = $request->all();

        // D. Upload Gambar (Opsional, Jika ada)
        if ($request->hasFile('gambar')) {
            // Simpan gambar ke folder 'public/barang_images'
            // Parameter kedua 'public' artinya simpan di disk public
            $path = $request->file('gambar')->store('barang_images', 'public');
            $data['gambar'] = $path;
        }

        // E. Simpan ke Database
        Barang::create($data);

        // F. Balik ke halaman index dengan pesan sukses
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }
}