<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Hardware',
            'Software',
            'Jaringan',
            'SIMRS',
            'Printer',
            'Internet',
            'Server',
            'CCTV',
            'Aplikasi',
            'User Account',
            'Perangkat Medis',
            'Lainnya',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'is_active' => true,
                ]
            );
        }
    }
}
