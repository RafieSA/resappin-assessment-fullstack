<?php

namespace App\Http\Controllers;

use App\Models\Barang; // Panggil Model Barang
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Panggil Storage buat urus gambar

class BarangController extends Controller
{
    // Fungsi buat nampilin halaman daftar barang
    public function index()
    {
        // Ambil data barang dari database, urutin dari yang terbaru
        $barangs = Barang::latest()->get(); 
        
        // Tampilkan view 'barang.index' sambil bawa data '$barangs'
        return view('barang.index', compact('barangs'));
    }

    // Fungsi buat nyimpen data barang baru
    public function store(Request $request)
    {
        // 1. Validasi dulu inputannya
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0', 
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // 2. Siapkan data yang mau disimpen
        $data = $request->all();

        // 3. Cek kalau ada upload gambar
        if ($request->hasFile('gambar')) {
            // Simpan gambar ke folder 'public/barang_images'
            $path = $request->file('gambar')->store('barang_images', 'public');
            $data['gambar'] = $path; // Masukin path gambar ke data
        }

        // 4. Simpan ke Database
        Barang::create($data);

        // 5. Balikin ke halaman index dengan pesan sukses
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }
}