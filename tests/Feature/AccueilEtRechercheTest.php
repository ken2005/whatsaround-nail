<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccueilEtRechercheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DiffusionSeeder::class);
    }

    public function test_accueil_retourne_200(): void
    {
        $response = $this->get(route('accueil'));
        $response->assertStatus(200);
    }

    public function test_accueil_affiche_la_vue_welcome(): void
    {
        $response = $this->get(route('accueil'));
        $response->assertViewIs('welcome');
    }

    public function test_recherche_retourne_200_sans_parametre(): void
    {
        $response = $this->get(route('recherche'));
        $response->assertStatus(200);
    }

    public function test_recherche_avec_param_search_retourne_200(): void
    {
        $response = $this->get(route('recherche', ['search' => 'test']));
        $response->assertStatus(200);
    }

    public function test_recherche_affiche_la_vue_recherche(): void
    {
        $response = $this->get(route('recherche'));
        $response->assertViewIs('evenement.recherche');
    }
}
