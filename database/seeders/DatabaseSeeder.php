<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // $this->call(CategorySeeder::class);
        // $this->call(AuthorSeeder::class);
        // $this->call(BookSeeder::class); 

        $this->call([
            CategorySeeder::class,
            AuthorSeeder::class,
            BookSeeder::class,
        ]); 
    }
}
