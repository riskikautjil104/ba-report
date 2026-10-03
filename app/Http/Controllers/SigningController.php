<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use App\Models\Participant;
use App\Services\SigningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SigningController extends Controller
{
    public function __construct(
        protected SigningService $signingService
    ) {}

    /**
     * Create a signing link for a participant.
     */
    public function requestSignature(Request $request, BeritaAcara $beritaAcara): RedirectResponse
    {
        Gate::authorize('view', $beritaAcara);

        $validated = $request->validate([
            'participant_id' => ['required', 'exists:participants,id'],
            'hours' => ['nullable', 'integer', 'min:1', 'max:168'],
        ]);

        $participant = Participant::where('berita_acara_id', $beritaAcara->id)
            ->findOrFail($validated['participant_id']);

        $hours = (int) ($validated['hours'] ?? 48);

        $sessionData = $this->signingService->createSession($beritaAcara, $participant, $hours);

        return back()->with([
            'success' => "Tautan tanda tangan untuk {$participant->nama} berhasil dibuat.",
            'signing_url' => $sessionData['url'],
            'signing_participant' => $participant->nama,
        ]);
    }

    /**
     * Public review and signing page.
     */
    public function show(string $token): View
    {
        $signingToken = $this->signingService->validateToken($token);

        if (! $signingToken) {
            return view('signing.invalid');
        }

        return view('signing.show', [
            'token' => $token,
            'session' => $signingToken,
            'beritaAcara' => $signingToken->beritaAcara,
            'participant' => $signingToken->participant,
        ]);
    }

    /**
     * Process the public signature submission.
     */
    public function sign(Request $request, string $token): RedirectResponse|View
    {
        $signingToken = $this->signingService->validateToken($token);

        if (! $signingToken) {
            return view('signing.invalid');
        }

        $validated = $request->validate([
            'signature' => ['required', 'string'],
        ]);

        $signature = $this->signingService->processSignature(
            $signingToken,
            $validated['signature'],
            $request->ip(),
            $request->userAgent()
        );

        return view('signing.success', [
            'beritaAcara' => $signingToken->beritaAcara,
            'participant' => $signingToken->participant,
            'signature' => $signature,
        ]);
    }
}
