<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AuditStatus;
use App\Enums\BaPriority;
use App\Enums\BaStatus;
use App\Enums\ParticipantType;
use App\Enums\UserRole;
use App\Http\Requests\BeritaAcara\StoreAttachmentRequest;
use App\Http\Requests\BeritaAcara\StoreBeritaAcaraRequest;
use App\Http\Requests\BeritaAcara\StoreHandlingLogRequest;
use App\Http\Requests\BeritaAcara\UpdateBeritaAcaraRequest;
use App\Models\Attachment;
use App\Models\AuditKebutuhan;
use App\Models\BeritaAcara;
use App\Models\Category;
use App\Models\HandlingLog;
use App\Models\Participant;
use App\Models\User;
use App\Services\AuditService;
use App\Services\BaNumberGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BeritaAcaraController extends Controller
{
    public function __construct(
        protected BaNumberGenerator $numberGenerator,
        protected AuditService $auditService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', BeritaAcara::class);

        $user = $request->user();
        $query = BeritaAcara::with(['category', 'creator', 'reporter'])
            ->forUser($user);

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhere('keluhan', 'like', "%{$search}%")
                    ->orWhereHas('participants', function ($pq) use ($search): void {
                        $pq->where('nama', 'like', "%{$search}%")
                            ->orWhere('unit', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->input('prioritas'));
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->input('kategori_id'));
        }

        $beritaAcaras = $query->latest('tanggal')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::active()->orderBy('name')->get();

        return view('berita-acara.index', [
            'beritaAcaras' => $beritaAcaras,
            'categories' => $categories,
            'statuses' => BaStatus::cases(),
            'priorities' => BaPriority::cases(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', BeritaAcara::class);

        $categories = Category::active()->orderBy('name')->get();
        $nextNomor = $this->numberGenerator->generate();

        $fromAudit = null;
        if ($request->filled('from_audit')) {
            $fromAuditParam = (string) $request->input('from_audit');
            $fromAudit = (new AuditKebutuhan)->resolveRouteBinding($fromAuditParam) ?? AuditKebutuhan::find($fromAuditParam);
        }

        $registeredVendors = User::where('role', UserRole::Vendor)->where('is_active', true)->orderBy('name')->get();

        return view('berita-acara.create', [
            'categories' => $categories,
            'nextNomor' => $nextNomor,
            'priorities' => BaPriority::cases(),
            'fromAudit' => $fromAudit,
            'registeredVendors' => $registeredVendors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBeritaAcaraRequest $request): RedirectResponse
    {
        Gate::authorize('create', BeritaAcara::class);

        $user = $request->user();

        $beritaAcara = DB::transaction(function () use ($request, $user): BeritaAcara {
            $nomor = $this->numberGenerator->generate();

            $ba = BeritaAcara::create([
                'nomor' => $nomor,
                'tanggal' => $request->input('tanggal'),
                'kategori_id' => $request->input('kategori_id'),
                'lokasi' => $request->input('lokasi'),
                'prioritas' => $request->input('prioritas'),
                'status' => $request->input('status', BaStatus::Draft->value),
                'keluhan' => $request->input('keluhan'),
                'hasil_pemeriksaan' => $request->input('hasil_pemeriksaan'),
                'penyebab' => $request->input('penyebab'),
                'tindakan' => $request->input('tindakan'),
                'kebutuhan' => $request->input('kebutuhan'),
                'nama_vendor' => $request->input('nama_vendor'),
                'kontak_vendor' => $request->input('kontak_vendor'),
                'catatan_vendor' => $request->input('catatan_vendor'),
                'kesimpulan' => $request->input('kesimpulan'),
                'tindak_lanjut' => $request->input('tindak_lanjut'),
                'created_by' => $user->id,
            ]);

            // Create primary reporter participant
            Participant::create([
                'berita_acara_id' => $ba->id,
                'nama' => $request->input('pelapor_nama'),
                'jabatan' => $request->input('pelapor_jabatan'),
                'unit' => $request->input('pelapor_unit'),
                'kontak' => $request->input('pelapor_kontak'),
                'participant_type' => ParticipantType::Pelapor,
            ]);

            // Add IT staff participant automatically
            Participant::create([
                'berita_acara_id' => $ba->id,
                'nama' => $user->name,
                'jabatan' => 'Teknisi IT / Ruang IT',
                'unit' => 'Ruang IT RSUD',
                'kontak' => $user->email,
                'participant_type' => ParticipantType::Teknisi,
            ]);

            // Create initial handling log
            HandlingLog::create([
                'berita_acara_id' => $ba->id,
                'user_id' => $user->id,
                'action' => 'Pendaftaran Berita Acara',
                'description' => 'Berita Acara berhasil dibuat dan didaftarkan ke sistem.',
                'created_at' => now(),
            ]);

            $this->auditService->log('create_berita_acara', $ba, null, $ba->toArray());

            if ($request->filled('from_audit_id')) {
                $audit = AuditKebutuhan::find($request->input('from_audit_id'));
                if ($audit) {
                    $audit->update([
                        'berita_acara_id' => $ba->id,
                        'status' => AuditStatus::Selesai,
                    ]);
                }
            }

            return $ba;
        });

        return redirect()->route('berita-acara.show', $beritaAcara)
            ->with('success', "Berita Acara {$beritaAcara->nomor} berhasil dibuat.");
    }

    /**
     * Display the specified resource.
     */
    public function show(BeritaAcara $beritaAcara): View
    {
        Gate::authorize('view', $beritaAcara);

        $beritaAcara->load([
            'category',
            'creator',
            'participants.signatures',
            'attachments.uploader',
            'handlingLogs.user',
            'auditLogs.user',
            'signatures.participant',
        ]);

        return view('berita-acara.show', [
            'beritaAcara' => $beritaAcara,
            'reporter' => $beritaAcara->reporter,
            'itStaff' => $beritaAcara->participants->firstWhere('participant_type', ParticipantType::Teknisi),
            'statuses' => BaStatus::cases(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BeritaAcara $beritaAcara): View
    {
        Gate::authorize('update', $beritaAcara);

        $categories = Category::active()->orderBy('name')->get();
        $reporter = $beritaAcara->reporter;

        $registeredVendors = User::where('role', UserRole::Vendor)->where('is_active', true)->orderBy('name')->get();

        return view('berita-acara.edit', [
            'beritaAcara' => $beritaAcara,
            'reporter' => $reporter,
            'categories' => $categories,
            'priorities' => BaPriority::cases(),
            'statuses' => BaStatus::cases(),
            'registeredVendors' => $registeredVendors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBeritaAcaraRequest $request, BeritaAcara $beritaAcara): RedirectResponse
    {
        Gate::authorize('update', $beritaAcara);

        $oldValues = $beritaAcara->toArray();

        DB::transaction(function () use ($request, $beritaAcara, $oldValues): void {
            $beritaAcara->update([
                'tanggal' => $request->input('tanggal'),
                'kategori_id' => $request->input('kategori_id'),
                'lokasi' => $request->input('lokasi'),
                'prioritas' => $request->input('prioritas'),
                'status' => $request->input('status'),
                'keluhan' => $request->input('keluhan'),
                'hasil_pemeriksaan' => $request->input('hasil_pemeriksaan'),
                'penyebab' => $request->input('penyebab'),
                'tindakan' => $request->input('tindakan'),
                'kebutuhan' => $request->input('kebutuhan'),
                'nama_vendor' => $request->input('nama_vendor'),
                'kontak_vendor' => $request->input('kontak_vendor'),
                'catatan_vendor' => $request->input('catatan_vendor'),
                'kesimpulan' => $request->input('kesimpulan'),
                'tindak_lanjut' => $request->input('tindak_lanjut'),
            ]);

            // Update or create reporter
            $reporter = $beritaAcara->reporter;
            if ($reporter) {
                $reporter->update([
                    'nama' => $request->input('pelapor_nama'),
                    'jabatan' => $request->input('pelapor_jabatan'),
                    'unit' => $request->input('pelapor_unit'),
                    'kontak' => $request->input('pelapor_kontak'),
                ]);
            } else {
                Participant::create([
                    'berita_acara_id' => $beritaAcara->id,
                    'nama' => $request->input('pelapor_nama'),
                    'jabatan' => $request->input('pelapor_jabatan'),
                    'unit' => $request->input('pelapor_unit'),
                    'kontak' => $request->input('pelapor_kontak'),
                    'participant_type' => ParticipantType::Pelapor,
                ]);
            }

            if ($oldValues['status'] !== $beritaAcara->status->value) {
                HandlingLog::create([
                    'berita_acara_id' => $beritaAcara->id,
                    'user_id' => Auth::id(),
                    'action' => 'Perubahan Status',
                    'description' => "Status diperbarui menjadi {$beritaAcara->status->label()}.",
                    'created_at' => now(),
                ]);
            }

            $this->auditService->log('update_berita_acara', $beritaAcara, $oldValues, $beritaAcara->fresh()->toArray());
        });

        return redirect()->route('berita-acara.show', $beritaAcara)
            ->with('success', 'Berita Acara berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BeritaAcara $beritaAcara): RedirectResponse
    {
        Gate::authorize('delete', $beritaAcara);

        $oldValues = $beritaAcara->toArray();

        $beritaAcara->delete();

        $this->auditService->log('delete_berita_acara', $beritaAcara, $oldValues, null);

        return redirect()->route('berita-acara.index')
            ->with('success', "Berita Acara {$beritaAcara->nomor} telah dihapus.");
    }

    /**
     * Add a timeline handling log entry.
     */
    public function storeHandlingLog(StoreHandlingLogRequest $request, BeritaAcara $beritaAcara): RedirectResponse
    {
        Gate::authorize('view', $beritaAcara);

        if ($beritaAcara->isFinalized()) {
            return back()->with('error', 'Berita Acara sudah difinalisasi.');
        }

        HandlingLog::create([
            'berita_acara_id' => $beritaAcara->id,
            'user_id' => Auth::id(),
            'action' => $request->input('action'),
            'description' => $request->input('description'),
            'created_at' => now(),
        ]);

        $this->auditService->log('add_handling_log', $beritaAcara, null, [
            'action' => $request->input('action'),
            'description' => $request->input('description'),
        ]);

        return back()->with('success', 'Catatan penanganan berhasil ditambahkan ke timeline.');
    }

    /**
     * Upload an attachment.
     */
    public function storeAttachment(StoreAttachmentRequest $request, BeritaAcara $beritaAcara): RedirectResponse
    {
        Gate::authorize('view', $beritaAcara);

        if ($beritaAcara->isFinalized()) {
            return back()->with('error', 'Berita Acara sudah difinalisasi.');
        }

        $file = $request->file('file');
        $filename = time().'_'.bin2hex(random_bytes(8)).'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('attachments/'.$beritaAcara->id, $filename, 'local');

        $attachment = Attachment::create([
            'berita_acara_id' => $beritaAcara->id,
            'uploaded_by' => Auth::id(),
            'disk' => 'local',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'description' => $request->input('description'),
        ]);

        HandlingLog::create([
            'berita_acara_id' => $beritaAcara->id,
            'user_id' => Auth::id(),
            'action' => 'Unggah Berkas',
            'description' => "Mengunggah berkas lampiran: {$attachment->original_name}",
            'created_at' => now(),
        ]);

        $this->auditService->log('upload_attachment', $beritaAcara, null, $attachment->toArray());

        return back()->with('success', 'Berkas lampiran berhasil diunggah.');
    }

    /**
     * Download or view an attachment securely.
     */
    public function downloadAttachment(BeritaAcara $beritaAcara, Attachment $attachment): StreamedResponse
    {
        Gate::authorize('view', $beritaAcara);

        if ($attachment->berita_acara_id !== $beritaAcara->id) {
            abort(404);
        }

        if (! Storage::disk($attachment->disk)->exists($attachment->path)) {
            abort(404, 'Berkas tidak ditemukan pada server.');
        }

        return Storage::disk($attachment->disk)->download(
            $attachment->path,
            $attachment->original_name,
            ['Content-Type' => $attachment->mime_type]
        );
    }

    /**
     * Archive the Berita Acara.
     */
    public function archive(BeritaAcara $beritaAcara): RedirectResponse
    {
        Gate::authorize('archive', $beritaAcara);

        $oldStatus = $beritaAcara->status->value;

        $beritaAcara->update([
            'status' => BaStatus::Diarsipkan,
        ]);

        HandlingLog::create([
            'berita_acara_id' => $beritaAcara->id,
            'user_id' => Auth::id(),
            'action' => 'Pengarsipan',
            'description' => 'Berita Acara resmi dipindahkan ke Arsip Permanen.',
            'created_at' => now(),
        ]);

        $this->auditService->log('archive_berita_acara', $beritaAcara, ['status' => $oldStatus], ['status' => BaStatus::Diarsipkan->value]);

        return redirect()->route('berita-acara.show', $beritaAcara)
            ->with('success', "Berita Acara {$beritaAcara->nomor} berhasil diarsipkan.");
    }

    /**
     * Display a listing of archived records.
     */
    public function archiveIndex(Request $request): View
    {
        Gate::authorize('viewAny', BeritaAcara::class);

        $user = $request->user();
        $query = BeritaAcara::with(['category', 'creator', 'reporter'])
            ->forUser($user)
            ->where('status', BaStatus::Diarsipkan);

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhere('keluhan', 'like', "%{$search}%");
            });
        }

        $beritaAcaras = $query->latest('tanggal')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('berita-acara.archive', [
            'beritaAcaras' => $beritaAcaras,
        ]);
    }

    /**
     * Display the printable official PDF view.
     */
    public function print(BeritaAcara $beritaAcara): View
    {
        Gate::authorize('view', $beritaAcara);

        $beritaAcara->load([
            'category',
            'creator',
            'participants.latestSignature',
            'attachments',
        ]);

        $verificationUrl = route('verify.show', $beritaAcara->getVerificationCode());

        return view('berita-acara.print', [
            'beritaAcara' => $beritaAcara,
            'reporter' => $beritaAcara->reporter,
            'itStaff' => $beritaAcara->participants->firstWhere('participant_type', ParticipantType::Teknisi),
            'verificationUrl' => $verificationUrl,
        ]);
    }
}
