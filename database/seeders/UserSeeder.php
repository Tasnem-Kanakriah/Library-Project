<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = ['name' => 'test admin', 'email' => 'a@a.com', "password" => "123", 'type' => 'admin'];
        $customer = ['name' => 'test customer', 'email' => 'c@c.com', "password" => "123"];
        User::create($admin);
        User::create($customer);
    }
}
