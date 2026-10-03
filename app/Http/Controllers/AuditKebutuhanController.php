<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AuditStatus;
use App\Enums\BaPriority;
use App\Enums\UserRole;
use App\Http\Requests\AuditKebutuhan\StoreAuditKebutuhanRequest;
use App\Http\Requests\AuditKebutuhan\UpdateAuditKebutuhanRequest;
use App\Models\AuditKebutuhan;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditKebutuhanController extends Controller
{
    /**
     * Display a listing of audit kebutuhan.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = AuditKebutuhan::with(['category', 'auditor'])
            ->forUser($user);

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhere('unit_kerja', 'like', "%{$search}%")
                    ->orWhere('nama_responden', 'like', "%{$search}%")
                    ->orWhere('keluhan_kendala', 'like', "%{$search}%")
                    ->orWhere('keinginan_harapan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->input('kategori_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->input('prioritas'));
        }

        $audits = $query->latest('id')->paginate(10)->withQueryString();

        return view('audit-kebutuhan.index', [
            'audits' => $audits,
            'categories' => Category::active()->orderBy('name')->get(),
            'statuses' => AuditStatus::cases(),
            'priorities' => BaPriority::cases(),
        ]);
    }

    /**
     * Show the form for creating a new audit kebutuhan.
     */
    public function create(Request $request): View
    {
        if ($request->user()->isVendor()) {
            abort(403, 'Rekanan vendor hanya memiliki hak akses pemantauan (lihat audit kebutuhan).');
        }

        return view('audit-kebutuhan.create', [
            'nextNomor' => AuditKebutuhan::generateNomor(),
            'categories' => Category::active()->orderBy('name')->get(),
            'priorities' => BaPriority::cases(),
            'statuses' => AuditStatus::cases(),
            'registeredVendors' => User::where('role', UserRole::Vendor)->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created audit kebutuhan in storage.
     */
    public function store(StoreAuditKebutuhanRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $nomor = AuditKebutuhan::generateNomor();

        $audit = AuditKebutuhan::create([
            'nomor' => $nomor,
            'tanggal_audit' => $validated['tanggal_audit'],
            'unit_kerja' => $validated['unit_kerja'],
            'lokasi_gedung' => $validated['lokasi_gedung'],
            'nama_responden' => $validated['nama_responden'],
            'jabatan_responden' => $validated['jabatan_responden'] ?? null,
            'kontak_responden' => $validated['kontak_responden'] ?? null,
            'kategori_id' => $validated['kategori_id'],
            'keluhan_kendala' => $validated['keluhan_kendala'],
            'keinginan_harapan' => $validated['keinginan_harapan'],
            'rekomendasi_it' => $validated['rekomendasi_it'] ?? null,
            'prioritas' => $validated['prioritas'],
            'status' => $validated['status'] ?? AuditStatus::SelesaiWawancara->value,
            'nama_vendor' => $validated['nama_vendor'] ?? null,
            'catatan_vendor' => $validated['catatan_vendor'] ?? null,
            'tanda_tangan_responden' => $validated['tanda_tangan_responden'] ?? null,
            'auditor_id' => $request->user()->id,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Pencatatan Audit Kebutuhan Baru',
            'old_values' => null,
            'new_values' => [
                'id' => $audit->id,
                'nomor' => $audit->nomor,
                'unit_kerja' => $audit->unit_kerja,
                'nama_responden' => $audit->nama_responden,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('audit-kebutuhan.show', $audit)
            ->with('success', "Audit kebutuhan '{$audit->nomor}' untuk unit {$audit->unit_kerja} berhasil dicatat.");
    }

    /**
     * Display the specified audit kebutuhan.
     */
    public function show(Request $request, AuditKebutuhan $auditKebutuhan): View
    {
        $auditKebutuhan->load(['category', 'auditor', 'beritaAcara']);

        if ($request->user()->isVendor() && ! in_array($auditKebutuhan->auditor?->role?->value, ['superadmin', 'staf'], true)) {
            abort(403, 'Akses tidak diizinkan. Rekanan vendor hanya dapat memantau audit yang dicatat oleh staf dan superadmin.');
        }

        return view('audit-kebutuhan.show', [
            'audit' => $auditKebutuhan,
        ]);
    }

    /**
     * Show the form for editing the specified audit kebutuhan.
     */
    public function edit(Request $request, AuditKebutuhan $auditKebutuhan): View
    {
        if ($request->user()->isVendor()) {
            abort(403, 'Rekanan vendor hanya memiliki hak akses pemantauan (lihat audit kebutuhan).');
        }

        return view('audit-kebutuhan.edit', [
            'audit' => $auditKebutuhan,
            'categories' => Category::active()->orderBy('name')->get(),
            'priorities' => BaPriority::cases(),
            'statuses' => AuditStatus::cases(),
            'registeredVendors' => User::where('role', UserRole::Vendor)->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified audit kebutuhan in storage.
     */
    public function update(UpdateAuditKebutuhanRequest $request, AuditKebutuhan $auditKebutuhan): RedirectResponse
    {
        $validated = $request->validated();
        $oldData = $auditKebutuhan->only([
            'unit_kerja', 'nama_responden', 'keluhan_kendala', 'keinginan_harapan', 'rekomendasi_it', 'status',
        ]);

        $auditKebutuhan->update($validated);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Pembaruan Data Audit Kebutuhan',
            'old_values' => $oldData,
            'new_values' => $auditKebutuhan->only([
                'unit_kerja', 'nama_responden', 'keluhan_kendala', 'keinginan_harapan', 'rekomendasi_it', 'status',
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('audit-kebutuhan.show', $auditKebutuhan)
            ->with('success', "Data audit kebutuhan '{$auditKebutuhan->nomor}' berhasil diperbarui.");
    }

    /**
     * Remove the specified audit kebutuhan from storage.
     */
    public function destroy(Request $request, AuditKebutuhan $auditKebutuhan): RedirectResponse
    {
        if ($request->user()->isVendor()) {
            abort(403, 'Rekanan vendor tidak diizinkan menghapus data audit kebutuhan.');
        }

        $nomor = $auditKebutuhan->nomor;
        $auditKebutuhan->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Penghapusan Audit Kebutuhan',
            'old_values' => ['nomor' => $nomor],
            'new_values' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('audit-kebutuhan.index')
            ->with('success', "Dokumen audit kebutuhan '{$nomor}' berhasil dihapus.");
    }

    /**
     * Print / Preview lembar hasil audit kebutuhan ruangan.
     */
    public function print(AuditKebutuhan $auditKebutuhan): View
    {
        $auditKebutuhan->load(['category', 'auditor']);

        return view('audit-kebutuhan.print', [
            'audit' => $auditKebutuhan,
            'auditKebutuhan' => $auditKebutuhan,
        ]);
    }
}
