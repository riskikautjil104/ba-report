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
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal')->index();
            $table->foreignId('kategori_id')->constrained('categories')->restrictOnDelete();
            $table->string('lokasi');
            $table->string('prioritas')->default('sedang')->index();
            $table->string('status')->default('draft')->index();
            $table->text('keluhan');
            $table->text('hasil_pemeriksaan')->nullable();
            $table->text('penyebab')->nullable();
            $table->text('tindakan')->nullable();
            $table->text('kebutuhan')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('finalized_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['created_by', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acaras');
    }
};
