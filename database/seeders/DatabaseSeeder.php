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
/* 
         \App\Models\User::factory()->create([
             'name' => 'admin',
             'email' => 'admin@example.com',
             'password' => 'teste@123',
             'role' => (int) '0',
        ]);  */
        \App\Models\User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => 'teste@123',
            'role' => (int) '1',
        ]);
/*         \App\Models\User::factory()->create([
            'name' => 'Manager 2',
            'email' => 'manager2@example.com',
            'password' => 'teste@123',
            'role' => (int) '1',
        ]);  */
    }
}
