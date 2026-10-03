<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BaPriority;
use App\Enums\BaStatus;
use App\Enums\ParticipantType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeritaAcara extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor',
        'tanggal',
        'kategori_id',
        'lokasi',
        'prioritas',
        'status',
        'keluhan',
        'hasil_pemeriksaan',
        'penyebab',
        'tindakan',
        'kebutuhan',
        'nama_vendor',
        'kontak_vendor',
        'catatan_vendor',
        'kesimpulan',
        'tindak_lanjut',
        'created_by',
        'finalized_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'prioritas' => BaPriority::class,
            'status' => BaStatus::class,
            'finalized_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function reporter(): HasOne
    {
        return $this->hasOne(Participant::class)
            ->where('participant_type', ParticipantType::Pelapor);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function handlingLogs(): HasMany
    {
        return $this->hasMany(HandlingLog::class)->orderBy('created_at', 'desc');
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(Signature::class);
    }

    public function signingTokens(): HasMany
    {
        return $this->hasMany(SigningToken::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class)->orderBy('created_at', 'desc');
    }

    public function isFinalized(): bool
    {
        return $this->finalized_at !== null || $this->status->isFinal();
    }

    public function canBeEditedBy(User $user): bool
    {
        if ($this->isFinalized()) {
            return false;
        }

        if ($user->isSuperadmin()) {
            return true;
        }

        return $this->created_by === $user->id && $this->status->isEditable();
    }

    public function getVerificationCode(): string
    {
        return substr(hash('sha256', 'rsud-chasan-ba:'.$this->id.':'.$this->nomor), 0, 16);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isSuperadmin() || $user->isDirektur()) {
            return $query;
        }

        return $query->where('created_by', $user->id);
    }
}
