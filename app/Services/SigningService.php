<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BaStatus;
use App\Enums\ParticipantType;
use App\Models\BeritaAcara;
use App\Models\HandlingLog;
use App\Models\Participant;
use App\Models\Signature;
use App\Models\SigningToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SigningService
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Create a secure signing session for a participant.
     *
     * @return array{token: string, session: SigningToken, url: string}
     */
    public function createSession(BeritaAcara $beritaAcara, Participant $participant, int $hoursValid = 48): array
    {
        // Invalidate active existing tokens for this participant
        SigningToken::where('berita_acara_id', $beritaAcara->id)
            ->where('participant_id', $participant->id)
            ->whereNull('used_at')
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now()]);

        $rawToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $rawToken);

        $session = SigningToken::create([
            'berita_acara_id' => $beritaAcara->id,
            'participant_id' => $participant->id,
            'token_hash' => $tokenHash,
            'expires_at' => now()->addHours($hoursValid),
        ]);

        if ($beritaAcara->status === BaStatus::Draft || $beritaAcara->status === BaStatus::DalamPenanganan) {
            $beritaAcara->update(['status' => BaStatus::MenungguTandaTangan]);
        }

        HandlingLog::create([
            'berita_acara_id' => $beritaAcara->id,
            'user_id' => auth()->id(),
            'action' => 'Permintaan Tanda Tangan',
            'description' => "Sesi tanda tangan dibuat untuk {$participant->nama} ({$participant->unit}).",
            'created_at' => now(),
        ]);

        $this->auditService->log('create_signing_session', $beritaAcara, null, [
            'participant_id' => $participant->id,
            'participant_name' => $participant->nama,
            'expires_at' => $session->expires_at->toIso8601String(),
        ]);

        return [
            'token' => $rawToken,
            'session' => $session,
            'url' => route('sign.show', $rawToken),
        ];
    }

    /**
     * Locate and validate a signing token.
     */
    public function validateToken(string $rawToken): ?SigningToken
    {
        $hash = hash('sha256', $rawToken);

        /** @var SigningToken|null $signingToken */
        $signingToken = SigningToken::with(['beritaAcara.category', 'beritaAcara.reporter', 'participant'])
            ->where('token_hash', $hash)
            ->first();

        if (! $signingToken || ! $signingToken->isValid()) {
            return null;
        }

        return $signingToken;
    }

    /**
     * Process and store the digital signature.
     */
    public function processSignature(
        SigningToken $signingToken,
        string $signatureBase64,
        ?string $ipAddress,
        ?string $userAgent
    ): Signature {
        return DB::transaction(function () use ($signingToken, $signatureBase64, $ipAddress, $userAgent): Signature {
            $beritaAcara = $signingToken->beritaAcara;
            $participant = $signingToken->participant;

            // Save signature data image
            $filename = 'signature_'.$beritaAcara->id.'_'.$participant->id.'_'.time().'.png';
            $path = 'signatures/'.$filename;

            // Extract base64 image data
            $imageData = $signatureBase64;
            if (str_contains($signatureBase64, ',')) {
                $parts = explode(',', $signatureBase64);
                $imageData = $parts[1];
            }

            Storage::disk('local')->put($path, base64_decode($imageData));

            $signature = Signature::create([
                'berita_acara_id' => $beritaAcara->id,
                'participant_id' => $participant->id,
                'signature_path' => $path,
                'signed_at' => now(),
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'token_id' => $signingToken->id,
            ]);

            $signingToken->update([
                'used_at' => now(),
            ]);

            HandlingLog::create([
                'berita_acara_id' => $beritaAcara->id,
                'user_id' => null,
                'action' => 'Tanda Tangan Elektronik',
                'description' => "Dokumen telah ditandatangani oleh {$participant->nama} ({$participant->participant_type->label()}).",
                'created_at' => now(),
            ]);

            // If primary reporter signed, update status to Selesai and finalize
            if ($participant->participant_type === ParticipantType::Pelapor) {
                $beritaAcara->update([
                    'status' => BaStatus::Selesai,
                    'finalized_at' => now(),
                ]);
            }

            $this->auditService->log('sign_document', $beritaAcara, null, [
                'participant_id' => $participant->id,
                'participant_name' => $participant->nama,
                'signed_at' => $signature->signed_at->toIso8601String(),
                'ip_address' => $ipAddress,
            ]);

            return $signature;
        });
    }
}
