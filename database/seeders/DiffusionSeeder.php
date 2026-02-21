<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiffusionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('diffusion')->insert([
            ['libelle' => 'Public', 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Invités uniquement', 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Abonnés uniquement', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
