<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AuditStatus;
use App\Enums\BaPriority;
use App\Enums\UserRole;
use App\Models\AuditKebutuhan;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditKebutuhanTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => UserRole::Staf,
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Jaringan & Internet',
            'slug' => 'jaringan-internet',
            'description' => 'Masalah jaringan dan internet RSUD',
            'is_active' => true,
        ]);
    }

    public function test_user_can_view_audit_kebutuhan_index(): void
    {
        AuditKebutuhan::create([
            'nomor' => 'AUD/IT/2026/10/0001',
            'tanggal_audit' => now()->toDateString(),
            'unit_kerja' => 'IGD Ponek',
            'lokasi_gedung' => 'Gedung A Lt 1',
            'nama_responden' => 'dr. Sarah',
            'kategori_id' => $this->category->id,
            'keluhan_kendala' => 'Koneksi WiFi sering putus saat resep',
            'keinginan_harapan' => 'Minta ditambah access point di nurse station',
            'prioritas' => BaPriority::Tinggi,
            'status' => AuditStatus::SelesaiWawancara,
            'auditor_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('audit-kebutuhan.index'));

        $response->assertOk()
            ->assertSee('AUD/IT/2026/10/0001')
            ->assertSee('IGD Ponek')
            ->assertSee('Koneksi WiFi sering putus');
    }

    public function test_user_can_create_audit_kebutuhan(): void
    {
        $response = $this->actingAs($this->user)->post(route('audit-kebutuhan.store'), [
            'tanggal_audit' => now()->toDateString(),
            'unit_kerja' => 'Poli Penyakit Dalam',
            'lokasi_gedung' => 'Gedung B Lt 2',
            'nama_responden' => 'Ns. Rahmat',
            'jabatan_responden' => 'Kepala Ruangan',
            'kontak_responden' => '08123456789',
            'kategori_id' => $this->category->id,
            'keluhan_kendala' => 'Printer barcode macet saat mencetak nomor resep',
            'keinginan_harapan' => 'Ganti printer barcode thermal baru dan kabel data',
            'rekomendasi_it' => 'Ajukan pengadaan printer barcode dan tes koneksi',
            'prioritas' => BaPriority::Sedang->value,
            'status' => AuditStatus::SelesaiWawancara->value,
            'nama_vendor' => 'PT. Datascrip',
            'catatan_vendor' => 'Pengadaan printer barcode thermal',
            'tanda_tangan_responden' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        ]);

        $this->assertDatabaseHas('audit_kebutuhans', [
            'unit_kerja' => 'Poli Penyakit Dalam',
            'nama_responden' => 'Ns. Rahmat',
            'nama_vendor' => 'PT. Datascrip',
            'tanda_tangan_responden' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        ]);

        $audit = AuditKebutuhan::where('unit_kerja', 'Poli Penyakit Dalam')->firstOrFail();
        $response->assertRedirect(route('audit-kebutuhan.show', $audit));
    }

    public function test_user_can_view_show_and_print_audit_kebutuhan(): void
    {
        $audit = AuditKebutuhan::create([
            'nomor' => 'AUD/IT/2026/10/0002',
            'tanggal_audit' => now()->toDateString(),
            'unit_kerja' => 'Ruang ICU',
            'lokasi_gedung' => 'Gedung C Lt 3',
            'nama_responden' => 'dr. Hendra, Sp.An',
            'kategori_id' => $this->category->id,
            'keluhan_kendala' => 'PC sentral monitor ICU lambat',
            'keinginan_harapan' => 'Upgrade RAM atau ganti unit PC baru',
            'prioritas' => BaPriority::Mendesak,
            'status' => AuditStatus::SelesaiWawancara,
            'auditor_id' => $this->user->id,
        ]);

        $showResponse = $this->actingAs($this->user)->get(route('audit-kebutuhan.show', $audit));
        $showResponse->assertOk()
            ->assertSee('Ruang ICU')
            ->assertSee('dr. Hendra, Sp.An');

        $printResponse = $this->actingAs($this->user)->get(route('audit-kebutuhan.print', $audit));
        $printResponse->assertOk()
            ->assertSee('LEMBAR AUDIT KEBUTUHAN & WAWANCARA UNIT', false)
            ->assertSee('Ruang ICU');
    }

    public function test_user_can_convert_audit_kebutuhan_to_berita_acara(): void
    {
        $audit = AuditKebutuhan::create([
            'nomor' => 'AUD/IT/2026/10/0003',
            'tanggal_audit' => now()->toDateString(),
            'unit_kerja' => 'Laboratorium PK',
            'lokasi_gedung' => 'Gedung Diagnostic Lt 1',
            'nama_responden' => 'Analis Andi',
            'kategori_id' => $this->category->id,
            'keluhan_kendala' => 'Alat LIS tidak tersambung ke database SIMRS',
            'keinginan_harapan' => 'Koneksikan interface LIS ke database SIMRS',
            'prioritas' => BaPriority::Tinggi,
            'status' => AuditStatus::SelesaiWawancara,
            'auditor_id' => $this->user->id,
        ]);

        $createBaResponse = $this->actingAs($this->user)
            ->get(route('berita-acara.create', ['from_audit' => $audit->id]));

        $createBaResponse->assertOk()
            ->assertSee('Dibuat Berdasarkan Lembar Audit Kebutuhan')
            ->assertSee('Laboratorium PK')
            ->assertSee('Analis Andi');

        // Store BA using from_audit_id
        $storeResponse = $this->actingAs($this->user)->post(route('berita-acara.store'), [
            'tanggal' => now()->toDateString(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Gedung Diagnostic Lt 1',
            'prioritas' => BaPriority::Tinggi->value,
            'pelapor_nama' => 'Analis Andi',
            'pelapor_jabatan' => 'Analis Kesehatan',
            'pelapor_unit' => 'Laboratorium PK',
            'keluhan' => 'Alat LIS tidak tersambung ke database SIMRS',
            'from_audit_id' => $audit->id,
        ]);

        $audit->refresh();
        $this->assertNotNull($audit->berita_acara_id);
        $this->assertEquals(AuditStatus::Selesai, $audit->status);
    }
}
