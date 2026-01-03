<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'رياضة'],
            ['name' => 'صحة'],
            ['name' => 'تغذية'],
            ['name' => 'تاريخ'],
            ['name' => 'جغرافيا'],
        ];
        Category::insert($categories);
    }
}
