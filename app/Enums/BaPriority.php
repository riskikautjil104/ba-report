<?php

declare(strict_types=1);

namespace App\Enums;

enum BaPriority: string
{
    case Rendah = 'rendah';
    case Sedang = 'sedang';
    case Tinggi = 'tinggi';
    case Mendesak = 'mendesak';

    public function label(): string
    {
        return match ($this) {
            self::Rendah => 'Rendah',
            self::Sedang => 'Sedang',
            self::Tinggi => 'Tinggi',
            self::Mendesak => 'Mendesak',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Rendah => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            self::Sedang => 'bg-sky-50 text-sky-700 border border-sky-200',
            self::Tinggi => 'bg-amber-50 text-amber-700 border border-amber-200',
            self::Mendesak => 'bg-rose-50 text-rose-700 border border-rose-200 animate-pulse',
        };
    }
}
