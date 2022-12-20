<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\BiayaAdmin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::create([
            'username' => 'admin',
            'password' => Hash::make('12345678'),
            'name' => 'admin',
            'alamat' => 'Cianjur',
            'role' => '1',
        ]);

        BiayaAdmin::create([
            'biaya_admin' => 5000,
            'tarif' => 5000
        ]);
    }
}