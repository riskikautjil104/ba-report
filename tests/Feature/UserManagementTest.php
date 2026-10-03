<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $superadmin;

    protected User $staf;

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
    }

    public function test_superadmin_can_view_user_list(): void
    {
        $response = $this->actingAs($this->superadmin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna Sistem');
        $response->assertSee($this->superadmin->name);
        $response->assertSee($this->staf->name);
    }

    public function test_non_superadmin_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->staf)->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_superadmin_can_create_new_user(): void
    {
        $payload = [
            'name' => 'Fajar Pratama',
            'username' => 'fajar_it',
            'email' => 'fajar@rsudchasan.id',
            'role' => UserRole::Staf->value,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->superadmin)->post(route('users.store'), $payload);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'fajar_it',
            'email' => 'fajar@rsudchasan.id',
            'role' => UserRole::Staf->value,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->superadmin->id,
            'action' => 'Pembuatan Pengguna Baru',
        ]);
    }

    public function test_superadmin_can_update_user_details(): void
    {
        $target = User::factory()->create([
            'name' => 'Nama Lama',
            'username' => 'user_lama',
            'email' => 'lama@rsudchasan.id',
            'role' => UserRole::Staf,
            'is_active' => true,
        ]);

        $payload = [
            'name' => 'Nama Baru',
            'username' => 'user_baru',
            'email' => 'baru@rsudchasan.id',
            'role' => UserRole::Superadmin->value,
            'password' => '',
            'password_confirmation' => '',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->superadmin)->put(route('users.update', $target), $payload);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'name' => 'Nama Baru',
            'username' => 'user_baru',
            'email' => 'baru@rsudchasan.id',
            'role' => UserRole::Superadmin->value,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->superadmin->id,
            'action' => 'Pembaruan Data Pengguna',
        ]);
    }

    public function test_superadmin_cannot_demote_or_deactivate_self(): void
    {
        $payload = [
            'name' => $this->superadmin->name,
            'username' => $this->superadmin->username,
            'email' => $this->superadmin->email,
            'role' => UserRole::Staf->value,
            'is_active' => '0',
        ];

        $response = $this->actingAs($this->superadmin)->put(route('users.update', $this->superadmin), $payload);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', [
            'id' => $this->superadmin->id,
            'role' => UserRole::Superadmin->value,
            'is_active' => true,
        ]);
    }

    public function test_superadmin_can_delete_other_user(): void
    {
        $target = User::factory()->create([
            'role' => UserRole::Staf,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->superadmin)->delete(route('users.destroy', $target));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $target->id,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->superadmin->id,
            'action' => 'Penghapusan Pengguna',
        ]);
    }

    public function test_superadmin_cannot_delete_self(): void
    {
        $response = $this->actingAs($this->superadmin)->delete(route('users.destroy', $this->superadmin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', [
            'id' => $this->superadmin->id,
        ]);
    }
}
