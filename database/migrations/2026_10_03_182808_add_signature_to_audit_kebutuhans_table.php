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
        Schema::table('audit_kebutuhans', function (Blueprint $table): void {
            $table->longText('tanda_tangan_responden')->nullable()->after('catatan_vendor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_kebutuhans', function (Blueprint $table): void {
            $table->dropColumn('tanda_tangan_responden');
        });
    }
};
