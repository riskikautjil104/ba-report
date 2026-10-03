<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case Superadmin = 'superadmin';
    case Staf = 'staf';
    case Direktur = 'direktur';
    case Vendor = 'vendor';

    public function label(): string
    {
        return match ($this) {
            self::Superadmin => 'Superadmin',
            self::Staf => 'Staf IT',
            self::Direktur => 'Direktur / Manajemen',
            self::Vendor => 'Rekanan Vendor / Pihak Ketiga',
        };
    }

    public function isSuperadmin(): bool
    {
        return $this === self::Superadmin;
    }

    public function isStaf(): bool
    {
        return $this === self::Staf;
    }

    public function isDirektur(): bool
    {
        return $this === self::Direktur;
    }

    public function isVendor(): bool
    {
        return $this === self::Vendor;
    }
}
