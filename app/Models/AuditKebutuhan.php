<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AuditStatus;
use App\Enums\BaPriority;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class AuditKebutuhan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor',
        'tanggal_audit',
        'unit_kerja',
        'lokasi_gedung',
        'nama_responden',
        'jabatan_responden',
        'kontak_responden',
        'kategori_id',
        'keluhan_kendala',
        'keinginan_harapan',
        'rekomendasi_it',
        'prioritas',
        'status',
        'nama_vendor',
        'catatan_vendor',
        'tanda_tangan_responden',
        'berita_acara_id',
        'auditor_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_audit' => 'date',
            'prioritas' => BaPriority::class,
            'status' => AuditStatus::class,
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function beritaAcara(): BelongsTo
    {
        return $this->belongsTo(BeritaAcara::class, 'berita_acara_id');
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isSuperadmin()) {
            return $query;
        }

        return $query->where('auditor_id', $user->id);
    }

    /**
     * Generate Nomor Audit Kebutuhan otomatis.
     * Format: AUD/IT/{YYYY}/{MM}/{XXXX}
     */
    public static function generateNomor(?CarbonInterface $date = null): string
    {
        $targetDate = $date ?? Carbon::now();
        $year = $targetDate->format('Y');
        $month = $targetDate->format('m');
        $prefix = "AUD/IT/{$year}/{$month}/";

        $latest = static::withTrashed()
            ->where('nomor', 'like', "{$prefix}%")
            ->orderBy('nomor', 'desc')
            ->first();

        if ($latest) {
            $parts = explode('/', $latest->nomor);
            $lastSeq = (int) end($parts);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }

        $formattedSeq = str_pad((string) $nextSeq, 4, '0', STR_PAD_LEFT);

        return "{$prefix}{$formattedSeq}";
    }
}
