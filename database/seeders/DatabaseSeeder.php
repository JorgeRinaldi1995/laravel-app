<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

       /*  \App\Models\User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
             'password' => 'teste@123',
             'role' => 'admin',
        ]); */
        \App\Models\User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => 'teste@123',
            'role' => 'manager',
        ]);
    }
}
