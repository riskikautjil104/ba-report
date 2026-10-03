<?php

declare(strict_types=1);

namespace App\Enums;

enum BaStatus: string
{
    case Draft = 'draft';
    case DalamPenanganan = 'dalam_penanganan';
    case Tertunda = 'tertunda';
    case MenungguTandaTangan = 'menunggu_tanda_tangan';
    case Selesai = 'selesai';
    case Diarsipkan = 'diarsipkan';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::DalamPenanganan => 'Dalam Penanganan',
            self::Tertunda => 'Tertunda',
            self::MenungguTandaTangan => 'Menunggu Tanda Tangan',
            self::Selesai => 'Selesai',
            self::Diarsipkan => 'Diarsipkan',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Draft => 'bg-slate-100 text-slate-700 border border-slate-200',
            self::DalamPenanganan => 'bg-sky-50 text-sky-700 border border-sky-200',
            self::Tertunda => 'bg-amber-50 text-amber-700 border border-amber-200',
            self::MenungguTandaTangan => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
            self::Selesai => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            self::Diarsipkan => 'bg-purple-50 text-purple-700 border border-purple-200',
        };
    }

    public function isEditable(): bool
    {
        return in_array($this, [self::Draft, self::DalamPenanganan, self::Tertunda], true);
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Selesai, self::Diarsipkan], true);
    }
}
