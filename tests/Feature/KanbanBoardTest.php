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

class KanbanBoardTest extends TestCase
{
    use RefreshDatabase;

    private User $superadmin;

    private User $staf;

    private User $direktur;

    private User $vendor;

    private Category $category;

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

        $this->direktur = User::factory()->create([
            'role' => UserRole::Direktur,
            'is_active' => true,
        ]);

        $this->vendor = User::factory()->create([
            'name' => 'PT Multi Medika Solusindo',
            'role' => UserRole::Vendor,
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Jaringan & Server',
            'slug' => 'jaringan-server',
            'is_active' => true,
        ]);
    }

    public function test_all_roles_can_access_kanban_board(): void
    {
        $ba = BeritaAcara::create([
            'nomor' => 'BA/IT/2026/10/0991',
            'tanggal' => now()->toDateString(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Ruang Server SIMRS',
            'prioritas' => BaPriority::Tinggi,
            'status' => BaStatus::DalamPenanganan,
            'pelapor_nama' => 'dr. Budi',
            'pelapor_unit' => 'SIMRS',
            'keluhan' => 'Koneksi database terputus',
            'nama_vendor' => 'PT Multi Medika Solusindo',
            'created_by' => $this->staf->id,
        ]);

        // 1. Staf can view
        $this->actingAs($this->staf)->get(route('kanban.index'))
            ->assertOk()
            ->assertSee('Papan Pemantauan Pekerjaan Vendor')
            ->assertSee('BA/IT/2026/10/0991');

        // 2. Superadmin can view
        $this->actingAs($this->superadmin)->get(route('kanban.index'))
            ->assertOk()
            ->assertSee('BA/IT/2026/10/0991');

        // 3. Direktur can view with executive monitoring badge
        $this->actingAs($this->direktur)->get(route('kanban.index'))
            ->assertOk()
            ->assertSee('Mode Pemantauan Eksekutif')
            ->assertSee('BA/IT/2026/10/0991');

        // 4. Vendor can view assigned work
        $this->actingAs($this->vendor)->get(route('kanban.index'))
            ->assertOk()
            ->assertSee('BA/IT/2026/10/0991');
    }

    public function test_staff_can_update_status_via_kanban(): void
    {
        $ba = BeritaAcara::create([
            'nomor' => 'BA/IT/2026/10/0992',
            'tanggal' => now()->toDateString(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Poli Mata',
            'prioritas' => BaPriority::Sedang,
            'status' => BaStatus::Draft,
            'pelapor_nama' => 'dr. Mata',
            'pelapor_unit' => 'Poli',
            'keluhan' => 'Instalasi printer',
            'nama_vendor' => 'PT Multi Medika Solusindo',
            'created_by' => $this->staf->id,
        ]);

        $response = $this->actingAs($this->staf)->patchJson(route('kanban.update-status', $ba), [
            'status' => BaStatus::DalamPenanganan->value,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'status' => BaStatus::DalamPenanganan->value,
            ]);

        $this->assertDatabaseHas('berita_acaras', [
            'id' => $ba->id,
            'status' => BaStatus::DalamPenanganan->value,
        ]);

        $this->assertDatabaseHas('handling_logs', [
            'berita_acara_id' => $ba->id,
            'action' => 'Pembaruan Status Kanban Board',
        ]);
    }

    public function test_direktur_cannot_modify_status_on_kanban(): void
    {
        $ba = BeritaAcara::create([
            'nomor' => 'BA/IT/2026/10/0993',
            'tanggal' => now()->toDateString(),
            'kategori_id' => $this->category->id,
            'lokasi' => 'Gedung Bedah Sentral',
            'prioritas' => BaPriority::Mendesak,
            'status' => BaStatus::Draft,
            'pelapor_nama' => 'dr. Bedah',
            'pelapor_unit' => 'IBS',
            'keluhan' => 'Pemasangan display monitor operasi',
            'nama_vendor' => 'PT Multi Medika Solusindo',
            'created_by' => $this->staf->id,
        ]);

        $response = $this->actingAs($this->direktur)->patchJson(route('kanban.update-status', $ba), [
            'status' => BaStatus::DalamPenanganan->value,
        ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('berita_acaras', [
            'id' => $ba->id,
            'status' => BaStatus::Draft->value,
        ]);
    }
}
