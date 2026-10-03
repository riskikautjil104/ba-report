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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BeritaAcaraWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $superadmin;

    protected User $staf;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superadmin = User::factory()->create([
            'role' => UserRole::Superadmin,
            'is_active' => true,
        ]);

        $this->staf = User::factory()->create([
            'role' => UserRole::Staf,
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Jaringan',
            'slug' => 'jaringan',
            'is_active' => true,
        ]);
    }

    public function test_user_can_view_berita_acara_index(): void
    {
        $response = $this->actingAs($this->staf)->get(route('berita-acara.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Berita Acara');
    }

    public function test_user_can_create_berita_acara_with_auto_numbering_and_reporter(): void
    {
        $payload = [
            'tanggal' => now()->format('Y-m-d'),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Gedung A Lantai 2 IGD',
            'prioritas' => BaPriority::Tinggi->value,
            'status' => BaStatus::Draft->value,
            'pelapor_nama' => 'dr. Budi Santoso',
            'pelapor_jabatan' => 'Dokter Jaga IGD',
            'pelapor_unit' => 'Instalasi Gawat Darurat',
            'pelapor_kontak' => '081234567890',
            'keluhan' => 'Koneksi jaringan LAN di IGD terputus total.',
            'hasil_pemeriksaan' => 'Kabel patch cord RJ45 tertekuk dan pin rusak.',
            'penyebab' => 'Pin konektor patah.',
            'tindakan' => 'Crimping ulang konektor RJ45 baru dan testing konektivitas.',
            'kebutuhan' => '1 buah konektor RJ45 Cat6.',
            'kesimpulan' => 'Jaringan LAN IGD kembali normal dan stabil.',
            'tindak_lanjut' => 'Monitoring berkala switch hub.',
        ];

        $response = $this->actingAs($this->staf)->post(route('berita-acara.store'), $payload);

        $this->assertDatabaseHas('berita_acaras', [
            'kategori_id' => $this->category->id,
            'lokasi' => 'Gedung A Lantai 2 IGD',
            'created_by' => $this->staf->id,
        ]);

        $ba = BeritaAcara::latest('id')->first();
        $this->assertNotNull($ba);
        $this->assertStringStartsWith('BA/IT/', $ba->nomor);

        // Verify reporter participant created
        $this->assertDatabaseHas('participants', [
            'berita_acara_id' => $ba->id,
            'nama' => 'dr. Budi Santoso',
            'unit' => 'Instalasi Gawat Darurat',
            'participant_type' => 'pelapor',
        ]);

        // Verify IT staff participant created
        $this->assertDatabaseHas('participants', [
            'berita_acara_id' => $ba->id,
            'nama' => $this->staf->name,
            'participant_type' => 'teknisi',
        ]);

        // Verify handling log created
        $this->assertDatabaseHas('handling_logs', [
            'berita_acara_id' => $ba->id,
            'action' => 'Pendaftaran Berita Acara',
        ]);

        $response->assertRedirect(route('berita-acara.show', $ba));
    }

    public function test_user_can_add_handling_log_to_timeline(): void
    {
        $ba = BeritaAcara::create([
            'nomor' => 'BA/IT/2026/10/0001',
            'tanggal' => now(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Poli Anak',
            'prioritas' => BaPriority::Sedang,
            'status' => BaStatus::DalamPenanganan,
            'keluhan' => 'Printer macet.',
            'created_by' => $this->staf->id,
        ]);

        $response = $this->actingAs($this->staf)->post(route('berita-acara.handling', $ba), [
            'action' => 'Penggantian Roller',
            'description' => 'Roller penarik kertas diganti dengan suku cadang baru.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('handling_logs', [
            'berita_acara_id' => $ba->id,
            'action' => 'Penggantian Roller',
        ]);
    }

    public function test_user_can_upload_and_download_attachment(): void
    {
        Storage::fake('local');

        $ba = BeritaAcara::create([
            'nomor' => 'BA/IT/2026/10/0002',
            'tanggal' => now(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Nurse Station ICU',
            'prioritas' => BaPriority::Sedang,
            'status' => BaStatus::Draft,
            'keluhan' => 'Kabel putus.',
            'created_by' => $this->staf->id,
        ]);

        $file = UploadedFile::fake()->create('foto_perbaikan.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($this->staf)->post(route('berita-acara.attachments.store', $ba), [
            'file' => $file,
            'description' => 'Foto dokumentasi fisik kabel sebelum dan sesudah perbaikan.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('attachments', [
            'berita_acara_id' => $ba->id,
            'original_name' => 'foto_perbaikan.jpg',
        ]);

        $attachment = $ba->fresh()->attachments()->first();
        $this->assertNotNull($attachment);

        // Download attachment
        $downloadResponse = $this->actingAs($this->staf)->get(route('berita-acara.attachments.download', [$ba, $attachment]));
        $downloadResponse->assertStatus(200);
    }

    public function test_digital_signature_workflow_and_verification(): void
    {
        $ba = BeritaAcara::create([
            'nomor' => 'BA/IT/2026/10/0003',
            'tanggal' => now(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Laboratorium',
            'prioritas' => BaPriority::Sedang,
            'status' => BaStatus::Draft,
            'keluhan' => 'PC tidak menyala.',
            'created_by' => $this->staf->id,
        ]);

        $reporter = $ba->participants()->create([
            'nama' => 'Ibu Rahmawati',
            'unit' => 'Laboratorium Patologi',
            'participant_type' => 'pelapor',
        ]);

        // Request signature link
        $reqResponse = $this->actingAs($this->staf)->post(route('berita-acara.request-signature', $ba), [
            'participant_id' => $reporter->id,
        ]);

        $reqResponse->assertSessionHas('signing_url');
        $signingUrl = session('signing_url');
        $token = basename($signingUrl);

        // Public review signing page
        $publicShowResponse = $this->get(route('sign.show', $token));
        $publicShowResponse->assertStatus(200);
        $publicShowResponse->assertSee('Ibu Rahmawati');
        $publicShowResponse->assertSee($ba->nomor);

        // Submit signature
        $dummySignature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        $signSubmitResponse = $this->post(route('sign.process', $token), [
            'signature' => $dummySignature,
        ]);

        $signSubmitResponse->assertStatus(200);
        $signSubmitResponse->assertSee('Tanda Tangan Berhasil Direkam!');

        // Verify status changed to Selesai and signature recorded
        $ba->refresh();
        $this->assertEquals(BaStatus::Selesai, $ba->status);
        $this->assertNotNull($ba->finalized_at);
        $this->assertDatabaseHas('signatures', [
            'berita_acara_id' => $ba->id,
            'participant_id' => $reporter->id,
        ]);

        // Public QR Verification endpoint
        $code = $ba->getVerificationCode();
        $verifyResponse = $this->get(route('verify.show', $code));
        $verifyResponse->assertStatus(200);
        $verifyResponse->assertSee($ba->nomor);
        $verifyResponse->assertSee('Dokumen Terverifikasi');
    }

    public function test_official_print_page_renders_with_official_letterhead(): void
    {
        $ba = BeritaAcara::create([
            'nomor' => 'BA/IT/2026/10/0004',
            'tanggal' => now(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Radiologi',
            'prioritas' => BaPriority::Sedang,
            'status' => BaStatus::Draft,
            'keluhan' => 'Monitor blank.',
            'created_by' => $this->staf->id,
        ]);

        $response = $this->actingAs($this->staf)->get(route('berita-acara.print', $ba));

        $response->assertStatus(200);
        $response->assertSee('Chasan Boesoirie');
        $response->assertSee('Berita Acara Penanganan');
        $response->assertSee($ba->nomor);
    }

    public function test_user_can_audit_needs_and_delegate_to_vendor(): void
    {
        $payload = [
            'tanggal' => now()->format('Y-m-d'),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Gedung Bedah Sentral (OK)',
            'prioritas' => BaPriority::Mendesak->value,
            'status' => BaStatus::DalamPenanganan->value,
            'pelapor_nama' => 'dr. Zulfikar, Sp.B',
            'pelapor_jabatan' => 'Kepala Instalasi Bedah',
            'pelapor_unit' => 'Kamar Operasi (OK)',
            'pelapor_kontak' => '081299887766',
            'keluhan' => 'Koneksi tele-radiologi dan PACS antar ruang operasi dan server utama sering time out.',
            'hasil_pemeriksaan' => 'Kabel FO multimode lama mengalami degradasi core dan redaman di atas 25dB.',
            'penyebab' => 'Jalur fiber optik luar gedung terjepit saat renovasi plafon.',
            'tindakan' => 'Audit kebutuhan jalur baru dan penugasan vendor rekanan fiber optik.',
            'kebutuhan' => 'Pengadaan 100m kabel Dropcore FO 24-core, pigtail SC, dan splicing 8 joint.',
            'nama_vendor' => 'PT. Maluku Fiber Solusi',
            'kontak_vendor' => '0811223344 (Bpk. Fajar)',
            'catatan_vendor' => 'Penarikan kabel jalur luar gedung, proteksi pipa conduit PVC, waktu pengerjaan 2 hari kerja.',
            'kesimpulan' => 'Diserahkan ke vendor untuk eksekusi instalasi fisik.',
            'tindak_lanjut' => 'Testing throughput dan redaman OTDR setelah terminasi selesai.',
        ];

        $response = $this->actingAs($this->staf)->post(route('berita-acara.store'), $payload);

        $ba = BeritaAcara::latest('id')->first();
        $this->assertNotNull($ba);
        $this->assertEquals('PT. Maluku Fiber Solusi', $ba->nama_vendor);
        $this->assertEquals('0811223344 (Bpk. Fajar)', $ba->kontak_vendor);
        $this->assertStringContainsString('Dropcore FO 24-core', $ba->kebutuhan);

        $response->assertRedirect(route('berita-acara.show', $ba));

        // Test Show view renders vendor card
        $showResponse = $this->actingAs($this->staf)->get(route('berita-acara.show', $ba));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('PT. Maluku Fiber Solusi');
        $showResponse->assertSee('0811223344 (Bpk. Fajar)');
        $showResponse->assertSee('Rekanan Vendor Pelaksana');

        // Test Print view renders vendor details
        $printResponse = $this->actingAs($this->staf)->get(route('berita-acara.print', $ba));
        $printResponse->assertStatus(200);
        $printResponse->assertSee('PT. Maluku Fiber Solusi');
        $printResponse->assertSee('0811223344 (Bpk. Fajar)');
    }
}
