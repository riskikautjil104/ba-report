<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ParticipantType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'berita_acara_id',
        'nama',
        'jabatan',
        'unit',
        'kontak',
        'participant_type',
    ];

    protected function casts(): array
    {
        return [
            'participant_type' => ParticipantType::class,
        ];
    }

    public function beritaAcara(): BelongsTo
    {
        return $this->belongsTo(BeritaAcara::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(Signature::class);
    }

    public function latestSignature(): HasOne
    {
        return $this->hasOne(Signature::class)->latestOfMany();
    }

    public function signingTokens(): HasMany
    {
        return $this->hasMany(SigningToken::class);
    }
}
