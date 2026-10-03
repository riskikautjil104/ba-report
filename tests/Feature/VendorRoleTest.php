<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AuditStatus;
use App\Enums\BaPriority;
use App\Enums\BaStatus;
use App\Enums\UserRole;
use App\Models\AuditKebutuhan;
use App\Models\BeritaAcara;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorRoleTest extends TestCase
{
    use RefreshDatabase;

    private User $vendor;

    private User $staf;

    private User $superadmin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vendor = User::factory()->create([
            'name' => 'PT Multi Medika Solusindo',
            'role' => UserRole::Vendor,
            'is_active' => true,
        ]);

        $this->staf = User::factory()->create([
            'role' => UserRole::Staf,
            'is_active' => true,
        ]);

        $this->superadmin = User::factory()->create([
            'role' => UserRole::Superadmin,
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Jaringan & Server',
            'slug' => 'jaringan-server',
            'is_active' => true,
        ]);
    }

    public function test_vendor_can_access_vendor_portal_dashboard(): void
    {
        $response = $this->actingAs($this->vendor)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Portal Rekanan Vendor IT RSUD Dr. H. Chasan Boesoirie');
        $response->assertSee('PT Multi Medika Solusindo');
    }

    public function test_vendor_can_view_assigned_berita_acara(): void
    {
        $baWithVendor = BeritaAcara::create([
            'nomor' => 'BA/IT/2026/10/0101',
            'tanggal' => now()->toDateString(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Server Farm Lt 3',
            'prioritas' => BaPriority::Tinggi,
            'status' => BaStatus::Draft,
            'pelapor_nama' => 'Staf IT',
            'pelapor_unit' => 'Ruang IT',
            'keluhan' => 'Switch Core mati',
            'nama_vendor' => 'PT Multi Medika Solusindo',
            'kontak_vendor' => '081299998888',
            'created_by' => $this->staf->id,
            'is_archived' => false,
        ]);

        $baInternalWithoutVendor = BeritaAcara::create([
            'nomor' => 'BA/IT/2026/10/0102',
            'tanggal' => now()->toDateString(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Poli Mata',
            'prioritas' => BaPriority::Rendah,
            'status' => BaStatus::Draft,
            'pelapor_nama' => 'dr. Mata',
            'pelapor_unit' => 'Poli',
            'keluhan' => 'Mouse rusak',
            'nama_vendor' => null,
            'created_by' => $this->staf->id,
            'is_archived' => false,
        ]);

        $response = $this->actingAs($this->vendor)->get(route('berita-acara.index'));
        $response->assertOk();
        $response->assertSee('BA/IT/2026/10/0101');
        $response->assertDontSee('BA/IT/2026/10/0102');

        $showResponse = $this->actingAs($this->vendor)->get(route('berita-acara.show', $baWithVendor));
        $showResponse->assertOk();
        $showResponse->assertSee('Server Farm Lt 3');
    }

    public function test_vendor_can_create_new_berita_acara_report(): void
    {
        $payload = [
            'tanggal' => now()->toDateString(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Ruang Server SIMRS',
            'prioritas' => BaPriority::Sedang->value,
            'status' => BaStatus::Draft->value,
            'pelapor_nama' => 'Ir. Hartono (Vendor Lead)',
            'pelapor_jabatan' => 'Field Engineer',
            'pelapor_unit' => 'Rekanan Pihak Ketiga',
            'pelapor_kontak' => '081234567890',
            'keluhan' => 'Penggantian modul PSU Switch Core 48 Port',
            'hasil_pemeriksaan' => 'PSU lama mengalami lonjakan voltase',
            'penyebab' => 'Komponen kapasitor aus',
            'tindakan' => 'Penggantian unit PSU redundant baru',
            'kebutuhan' => '1 unit sparepart modul asli',
            'nama_vendor' => 'PT Multi Medika Solusindo',
            'kontak_vendor' => '081299998888',
            'catatan_vendor' => 'Garansi penggantian unit berlaku 1 tahun kalender',
            'kesimpulan' => 'Perangkat switch kembali normal redundant',
            'tindak_lanjut' => 'Pemantauan suhu rak server',
        ];

        $response = $this->actingAs($this->vendor)->post(route('berita-acara.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('berita_acaras', [
            'nama_vendor' => 'PT Multi Medika Solusindo',
            'created_by' => $this->vendor->id,
        ]);
    }

    public function test_vendor_can_view_audit_kebutuhan_created_by_staff_and_superadmin(): void
    {
        $auditStaff = AuditKebutuhan::create([
            'nomor' => 'AUD/IT/2026/10/0881',
            'tanggal_audit' => now()->toDateString(),
            'unit_kerja' => 'Instalasi Radiologi',
            'lokasi_gedung' => 'Gedung Penunjang Medis Lt 1',
            'nama_responden' => 'dr. Radiologi',
            'kategori_id' => $this->category->id,
            'keluhan_kendala' => 'Jaringan PACS gambar radiologi terputus ke SIMRS',
            'keinginan_harapan' => 'Kabel fiber optik diperiksa oleh vendor rekanan',
            'prioritas' => BaPriority::Mendesak,
            'status' => AuditStatus::SelesaiWawancara,
            'auditor_id' => $this->staf->id,
        ]);

        $auditSuperadmin = AuditKebutuhan::create([
            'nomor' => 'AUD/IT/2026/10/0882',
            'tanggal_audit' => now()->toDateString(),
            'unit_kerja' => 'Laboratorium PK',
            'lokasi_gedung' => 'Gedung Lab Lt 2',
            'nama_responden' => 'dr. Lab PK',
            'kategori_id' => $this->category->id,
            'keluhan_kendala' => 'Alat hema analyzer butuh integrasi LIS',
            'keinginan_harapan' => 'Pemasangan interface RS232 ke LAN',
            'prioritas' => BaPriority::Tinggi,
            'status' => AuditStatus::SelesaiWawancara,
            'auditor_id' => $this->superadmin->id,
        ]);

        $response = $this->actingAs($this->vendor)->get(route('audit-kebutuhan.index'));
        $response->assertOk();
        $response->assertSee('AUD/IT/2026/10/0881');
        $response->assertSee('AUD/IT/2026/10/0882');
        $response->assertSee('Instalasi Radiologi');
        $response->assertSee('Laboratorium PK');
        $response->assertDontSee('+ Catat Hasil Wawancara Audit');

        $showResponse = $this->actingAs($this->vendor)->get(route('audit-kebutuhan.show', $auditStaff));
        $showResponse->assertOk();
        $showResponse->assertSee('Jaringan PACS gambar radiologi terputus ke SIMRS');
    }

    public function test_vendor_cannot_create_or_modify_or_delete_audit_kebutuhan(): void
    {
        $audit = AuditKebutuhan::create([
            'nomor' => 'AUD/IT/2026/10/0883',
            'tanggal_audit' => now()->toDateString(),
            'unit_kerja' => 'Farmasi Rawat Jalan',
            'lokasi_gedung' => 'Gedung B Lt 1',
            'nama_responden' => 'Apt. Rina',
            'kategori_id' => $this->category->id,
            'keluhan_kendala' => 'Printer etiket obat sering macet',
            'keinginan_harapan' => 'Ganti thermal head printer',
            'prioritas' => BaPriority::Sedang,
            'status' => AuditStatus::SelesaiWawancara,
            'auditor_id' => $this->staf->id,
        ]);

        $this->actingAs($this->vendor)->get(route('audit-kebutuhan.create'))
            ->assertForbidden();

        $this->actingAs($this->vendor)->post(route('audit-kebutuhan.store'), [
            'tanggal_audit' => now()->toDateString(),
            'unit_kerja' => 'Unit Terlarang',
            'lokasi_gedung' => 'Gedung X',
            'nama_responden' => 'Responden X',
            'kategori_id' => $this->category->id,
            'keluhan_kendala' => 'Keluhan X',
            'keinginan_harapan' => 'Harapan X',
            'prioritas' => BaPriority::Sedang->value,
        ])->assertForbidden();

        $this->actingAs($this->vendor)->get(route('audit-kebutuhan.edit', $audit))
            ->assertForbidden();

        $this->actingAs($this->vendor)->delete(route('audit-kebutuhan.destroy', $audit))
            ->assertForbidden();
    }

    public function test_vendor_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->vendor)->get(route('users.index'));
        $response->assertForbidden();
    }
}
