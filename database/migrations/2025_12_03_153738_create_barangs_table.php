<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang'); // Untuk Nama
            $table->integer('harga_beli'); // Untuk Harga Beli (Angka)
            $table->integer('harga_jual'); // Untuk Harga Jual (Angka)
            $table->integer('stok');       // Untuk Stok (Angka)
            $table->string('gambar')->nullable(); // Untuk Gambar (Boleh kosong)
            $table->timestamps(); // Otomatis bikin kolom created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
