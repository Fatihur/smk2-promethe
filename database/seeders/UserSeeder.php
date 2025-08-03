<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@smk2sumbawa.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create panitia user
        User::create([
            'name' => 'Panitia PPDB',
            'username' => 'panitia',
            'email' => 'panitia@smk2sumbawa.sch.id',
            'password' => Hash::make('panitia123'),
            'role' => 'panitia',
            'is_active' => true,
        ]);
    }
}
