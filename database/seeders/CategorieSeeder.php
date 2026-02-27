<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Sport',
            'Culture',
            'Soirée',
            'Formation',
            'Concert',
            'Repas',
            'Nature',
            'Tech',
            'Associatif',
            'Autre',
        ];

        foreach ($categories as $libelle) {
            $exists = DB::table('categorie')->where('libelle', $libelle)->exists();

            if (! $exists) {
                DB::table('categorie')->insert([
                    'libelle' => $libelle,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
