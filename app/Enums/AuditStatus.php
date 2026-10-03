<?php

declare(strict_types=1);

namespace App\Enums;

enum AuditStatus: string
{
    case Draft = 'draft';
    case SelesaiWawancara = 'selesai_wawancara';
    case Dianalisis = 'dianalisis';
    case DiserahkanKeVendor = 'diserahkan_ke_vendor';
    case Selesai = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft Wawancara',
            self::SelesaiWawancara => 'Wawancara Selesai',
            self::Dianalisis => 'Analisis Teknis IT',
            self::DiserahkanKeVendor => 'Diserahkan ke Vendor',
            self::Selesai => 'Selesai Terpenuhi',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Draft => 'bg-slate-100 text-slate-700 border border-slate-200',
            self::SelesaiWawancara => 'bg-sky-100 text-sky-800 border border-sky-200',
            self::Dianalisis => 'bg-amber-100 text-amber-800 border border-amber-200',
            self::DiserahkanKeVendor => 'bg-purple-100 text-purple-800 border border-purple-200',
            self::Selesai => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
        };
    }
}
