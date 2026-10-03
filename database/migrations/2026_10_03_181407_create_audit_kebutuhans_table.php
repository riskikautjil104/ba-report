<?php

declare(strict_types=1);

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
        Schema::create('audit_kebutuhans', function (Blueprint $table): void {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal_audit');
            $table->string('unit_kerja');
            $table->string('lokasi_gedung');
            $table->string('nama_responden');
            $table->string('jabatan_responden')->nullable();
            $table->string('kontak_responden')->nullable();
            $table->foreignId('kategori_id')->constrained('categories')->cascadeOnUpdate();
            $table->text('keluhan_kendala');
            $table->text('keinginan_harapan');
            $table->text('rekomendasi_it')->nullable();
            $table->string('prioritas')->default('sedang');
            $table->string('status')->default('draft');
            $table->string('nama_vendor')->nullable();
            $table->text('catatan_vendor')->nullable();
            $table->foreignId('berita_acara_id')->nullable()->constrained('berita_acaras')->nullOnDelete();
            $table->foreignId('auditor_id')->constrained('users')->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_kebutuhans');
    }
};
