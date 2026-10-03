<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BeritaAcara;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class BaNumberGenerator
{
    /**
     * Generate the next official Berita Acara number.
     * Format: BA/IT/{YYYY}/{MM}/{XXXX}
     */
    public function generate(?CarbonInterface $date = null): string
    {
        $targetDate = $date ?? Carbon::now();
        $year = $targetDate->format('Y');
        $month = $targetDate->format('m');
        $prefix = "BA/IT/{$year}/{$month}/";

        $latest = BeritaAcara::withTrashed()
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
