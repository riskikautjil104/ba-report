<?php

declare(strict_types=1);

namespace App\Enums;

enum ParticipantType: string
{
    case Pelapor = 'pelapor';
    case Teknisi = 'teknisi';
    case Mengetahui = 'mengetahui';

    public function label(): string
    {
        return match ($this) {
            self::Pelapor => 'Pelapor / Pengguna',
            self::Teknisi => 'Petugas / Teknisi IT',
            self::Mengetahui => 'Pejabat / Mengetahui',
        };
    }
}
