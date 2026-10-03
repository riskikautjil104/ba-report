<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\BaPriority;
use App\Enums\BaStatus;
use App\Models\BeritaAcara;
use App\Models\Category;
use App\Models\HandlingLog;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class KanbanController extends Controller
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Display the Kanban Board for monitoring vendor and IT work orders.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = BeritaAcara::with(['category', 'reporter', 'creator'])
            ->forUser($user)
            ->where('status', '!=', BaStatus::Diarsipkan);

        // Filter default / toggle: Vendor Only
        $vendorOnly = $request->boolean('vendor_only', true);
        if ($vendorOnly && ! $user->isVendor()) {
            $query->whereNotNull('nama_vendor')->where('nama_vendor', '!=', '');
        }

        if ($request->filled('vendor')) {
            $query->where('nama_vendor', $request->input('vendor'));
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->input('kategori_id'));
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->input('prioritas'));
        }

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhere('keluhan', 'like', "%{$search}%")
                    ->orWhere('tindakan', 'like', "%{$search}%")
                    ->orWhere('nama_vendor', 'like', "%{$search}%");
            });
        }

        $allCards = $query->orderBy('tanggal', 'desc')->get();

        // Columns definition for the workflow
        $columns = [
            BaStatus::Draft->value => [
                'status' => BaStatus::Draft,
                'title' => '1. Baru / Penugasan',
                'description' => 'Disposisi pekerjaan atau audit yang diserahkan ke vendor',
                'color' => 'slate',
                'cards' => $allCards->where('status', BaStatus::Draft)->values(),
            ],
            BaStatus::DalamPenanganan->value => [
                'status' => BaStatus::DalamPenanganan,
                'title' => '2. Sedang Dikerjakan',
                'description' => 'Vendor / teknisi sedang melakukan pengerjaan di lokasi',
                'color' => 'sky',
                'cards' => $allCards->where('status', BaStatus::DalamPenanganan)->values(),
            ],
            BaStatus::Tertunda->value => [
                'status' => BaStatus::Tertunda,
                'title' => '3. Tertunda / Pending',
                'description' => 'Menunggu suku cadang, perangkat pengganti, atau vendor',
                'color' => 'amber',
                'cards' => $allCards->where('status', BaStatus::Tertunda)->values(),
            ],
            BaStatus::MenungguTandaTangan->value => [
                'status' => BaStatus::MenungguTandaTangan,
                'title' => '4. Verifikasi & TTD',
                'description' => 'Pekerjaan selesai, menunggu uji fungsi dan tanda tangan ruangan',
                'color' => 'indigo',
                'cards' => $allCards->where('status', BaStatus::MenungguTandaTangan)->values(),
            ],
            BaStatus::Selesai->value => [
                'status' => BaStatus::Selesai,
                'title' => '5. Selesai & Tuntas',
                'description' => 'Serah terima pekerjaan sah dan tuntas',
                'color' => 'emerald',
                'cards' => $allCards->where('status', BaStatus::Selesai)->values(),
            ],
        ];

        $vendorsList = BeritaAcara::whereNotNull('nama_vendor')
            ->where('nama_vendor', '!=', '')
            ->distinct()
            ->orderBy('nama_vendor')
            ->pluck('nama_vendor');

        $categories = Category::active()->orderBy('name')->get();
        $priorities = BaPriority::cases();

        return view('kanban.index', [
            'columns' => $columns,
            'vendorsList' => $vendorsList,
            'categories' => $categories,
            'priorities' => $priorities,
            'vendorOnly' => $vendorOnly,
            'totalCards' => $allCards->count(),
            'canManage' => ! $user->isDirektur(),
        ]);
    }

    /**
     * Update the status of a Berita Acara via drag-and-drop or select.
     */
    public function updateStatus(Request $request, BeritaAcara $beritaAcara): JsonResponse
    {
        $user = $request->user();

        if ($user->isDirektur()) {
            return response()->json([
                'success' => false,
                'message' => 'Role Direktur / Manajemen hanya memiliki akses pemantauan eksekutif.',
            ], 403);
        }

        if ($user->isVendor() && $beritaAcara->created_by !== $user->id && empty($beritaAcara->nama_vendor)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki otorisasi untuk memperbarui pekerjaan ini.',
            ], 403);
        }

        $validated = $request->validate([
            'status' => ['required', new Enum(BaStatus::class)],
        ]);

        $oldStatus = $beritaAcara->status;
        $newStatus = BaStatus::from($validated['status']);

        if ($oldStatus === $newStatus) {
            return response()->json([
                'success' => true,
                'message' => 'Status tidak berubah.',
            ]);
        }

        $beritaAcara->update([
            'status' => $newStatus,
            'finalized_at' => in_array($newStatus, [BaStatus::Selesai, BaStatus::Diarsipkan], true) ? now() : null,
        ]);

        HandlingLog::create([
            'berita_acara_id' => $beritaAcara->id,
            'user_id' => $user->id,
            'action' => 'Pembaruan Status Kanban Board',
            'description' => "Status pekerjaan dipindahkan dari '{$oldStatus->label()}' ke '{$newStatus->label()}' oleh {$user->name}.",
            'created_at' => now(),
        ]);

        $this->auditService->log(
            'kanban_update_status',
            $beritaAcara,
            ['status' => $oldStatus->value],
            ['status' => $newStatus->value]
        );

        return response()->json([
            'success' => true,
            'message' => "Pekerjaan {$beritaAcara->nomor} berhasil dipindahkan ke '{$newStatus->label()}'.",
            'status' => $newStatus->value,
            'label' => $newStatus->label(),
            'badge_class' => $newStatus->badgeClasses(),
        ]);
    }
}
