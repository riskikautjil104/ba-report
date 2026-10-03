<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@rsudchasan.id'],
            [
                'name' => 'Superadmin Ruang IT',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => UserRole::Superadmin,
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'staf@rsudchasan.id'],
            [
                'name' => 'Staf Ruang IT',
                'username' => 'staf',
                'password' => Hash::make('password'),
                'role' => UserRole::Staf,
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'direktur@rsudchasan.id'],
            [
                'name' => 'Direktur RSUD Chasan Boesoirie',
                'username' => 'direktur',
                'password' => Hash::make('password'),
                'role' => UserRole::Direktur,
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'vendor@rsudchasan.id'],
            [
                'name' => 'PT Multi Medika Solusindo (Vendor)',
                'username' => 'vendor',
                'password' => Hash::make('password'),
                'role' => UserRole::Vendor,
                'is_active' => true,
            ]
        );

        $this->call(CategorySeeder::class);
    }
}
