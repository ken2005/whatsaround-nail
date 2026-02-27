<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiffusionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            'Public',
            'Invités uniquement',
            'Abonnés uniquement',
        ];

        foreach ($rows as $libelle) {
            $exists = DB::table('diffusion')->where('libelle', $libelle)->exists();

            if (! $exists) {
                DB::table('diffusion')->insert([
                    'libelle' => $libelle,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
