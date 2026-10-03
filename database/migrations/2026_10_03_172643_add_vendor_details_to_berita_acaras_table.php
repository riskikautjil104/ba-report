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
        Schema::table('berita_acaras', function (Blueprint $table) {
            $table->string('nama_vendor')->nullable()->after('kebutuhan');
            $table->string('kontak_vendor')->nullable()->after('nama_vendor');
            $table->text('catatan_vendor')->nullable()->after('kontak_vendor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita_acaras', function (Blueprint $table) {
            $table->dropColumn(['nama_vendor', 'kontak_vendor', 'catatan_vendor']);
        });
    }
};
