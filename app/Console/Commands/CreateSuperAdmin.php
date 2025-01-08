<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'make:superadmin';
    protected $description = 'Crée un super administrateur par défaut si aucun n\'existe';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Vérifiez si un super_admin existe déjà
        $existingSuperAdmin = User::where('role', 'super_admin')->first();
        if ($existingSuperAdmin) {
            $this->info('Un super administrateur existe déjà.');
            return;
        }

        // Créez un super_admin par défaut
        $superAdmin = User::create([
            'name' => 'Billy bil',
            'email' => 'billybil@gmail.com',
            'password' => Hash::make('password1234'),
            'role' => 'super_admin',
        ]);

        $this->info('Super administrateur créé avec succès :');
        $this->info('Email : billybil@gmail.com');
        $this->info('Mot de passe : password1234');
    }
}