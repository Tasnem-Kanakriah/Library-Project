<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $books = [
        //     ['name' => "author 1"],
        //     ['name' => "author 2"],
        //     ['name' => "author 3"],
        //     ['name' => "author 4"],
        //     ['name' => "author 5"],
        //     ['name' => "author 6"],
        // ];
        // Author::insert($books);

        Author::factory(10)->create();
    }
}
