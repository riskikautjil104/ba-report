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

class DirekturRoleTest extends TestCase
{
    use RefreshDatabase;

    private User $direktur;

    private User $staf;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->direktur = User::factory()->create([
            'role' => UserRole::Direktur,
            'is_active' => true,
        ]);

        $this->staf = User::factory()->create([
            'role' => UserRole::Staf,
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Jaringan & Internet',
            'slug' => 'jaringan-internet',
            'is_active' => true,
        ]);
    }

    public function test_direktur_can_access_executive_dashboard(): void
    {
        $response = $this->actingAs($this->direktur)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard Eksekutif Direktur');
        $response->assertSee('Wawancara Audit Ruangan');
        $response->assertSee('Penugasan Vendor');
    }

    public function test_direktur_can_view_all_berita_acara_created_by_staff(): void
    {
        $ba = BeritaAcara::create([
            'nomor' => 'BA/IT/2026/10/0001',
            'tanggal' => now()->toDateString(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Gedung Radiologi Lt 1',
            'prioritas' => BaPriority::Tinggi,
            'status' => BaStatus::Draft,
            'pelapor_nama' => 'dr. Radiologi',
            'pelapor_jabatan' => 'Kepala Ruangan',
            'pelapor_unit' => 'Radiologi',
            'pelapor_kontak' => '08123456789',
            'keluhan' => 'Kabel LAN putus',
            'hasil_pemeriksaan' => 'Konektor RJ45 patah',
            'penyebab' => 'Tertarik meja',
            'tindakan' => 'Crimping ulang',
            'created_by' => $this->staf->id,
            'is_archived' => false,
        ]);

        $response = $this->actingAs($this->direktur)->get(route('berita-acara.index'));
        $response->assertOk();
        $response->assertSee('Gedung Radiologi Lt 1');

        $showResponse = $this->actingAs($this->direktur)->get(route('berita-acara.show', $ba));
        $showResponse->assertOk();
        $showResponse->assertSee('Gedung Radiologi Lt 1');
    }

    public function test_direktur_can_view_all_audit_kebutuhan_created_by_staff(): void
    {
        AuditKebutuhan::create([
            'nomor' => 'AUD/IT/2026/10/0001',
            'tanggal_audit' => now()->toDateString(),
            'unit_kerja' => 'Ruang ICU Melati',
            'lokasi_gedung' => 'Gedung Teratai Lt 2',
            'nama_responden' => 'Ns. Fatimah',
            'kategori_id' => $this->category->id,
            'keluhan_kendala' => 'Komputer monitoring lambat saat input data',
            'keinginan_harapan' => 'Upgrade RAM atau ganti SSD',
            'prioritas' => BaPriority::Mendesak,
            'status' => AuditStatus::SelesaiWawancara,
            'auditor_id' => $this->staf->id,
        ]);

        $response = $this->actingAs($this->direktur)->get(route('audit-kebutuhan.index'));
        $response->assertOk();
        $response->assertSee('Ruang ICU Melati');
        $response->assertSee('Ns. Fatimah');
    }

    public function test_direktur_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->direktur)->get(route('users.index'));
        $response->assertForbidden();
    }
}
