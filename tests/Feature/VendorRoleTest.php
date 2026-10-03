<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\BaPriority;
use App\Enums\BaStatus;
use App\Enums\UserRole;
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

    public function test_vendor_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->vendor)->get(route('users.index'));
        $response->assertForbidden();
    }
}
