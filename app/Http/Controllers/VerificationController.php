<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use Illuminate\View\View;

class VerificationController extends Controller
{
    /**
     * Public QR verification endpoint.
     */
    public function verify(string $code): View
    {
        // Search through recent or all berita acaras for matching verification code
        $beritaAcara = BeritaAcara::with(['category', 'participants.latestSignature'])
            ->get()
            ->first(fn (BeritaAcara $ba): bool => hash_equals($ba->getVerificationCode(), $code));

        if (! $beritaAcara) {
            return view('verification.not-found', ['code' => $code]);
        }

        return view('verification.show', [
            'beritaAcara' => $beritaAcara,
            'code' => $code,
        ]);
    }
}
