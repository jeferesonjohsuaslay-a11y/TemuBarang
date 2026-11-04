<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori');

            $table->string('jenis', 20);                // hilang | ditemukan
            $table->string('status', 20);               // hilang | ditemukan | selesai

            $table->string('nama_barang', 100);
            $table->text('deskripsi');
            $table->string('lokasi', 150);
            $table->date('tanggal_kejadian');
            $table->string('foto')->nullable();
            $table->string('kontak', 30);

            $table->timestamps();

            $table->index(['jenis', 'status']);
            $table->index('tanggal_kejadian');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_barang');
    }
};
