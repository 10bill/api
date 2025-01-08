<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Ajoutez cette ligne


class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créez un utilisateur admin
        User::create([
            'name' => 'Billy',
            'email' => 'billy@gmail.com',
            'password' => Hash::make('password123'), // Assurez-vous que le mot de passe est haché
            'role' => 'admin',
        ]);
    }
}