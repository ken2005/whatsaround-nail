<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilSuivreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DiffusionSeeder::class);
    }

    public function test_profil_sans_connexion_redirige_connexion(): void
    {
        $user = User::factory()->create();
        $response = $this->get(route('profil', $user->id));
        $response->assertRedirect(route('connexion'));
    }

    public function test_profil_connecte_retourne_200(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('profil', $user->id));
        $response->assertStatus(200);
        $response->assertViewIs('user.profil');
        $response->assertSee($user->name);
    }

    public function test_abonnements_sans_connexion_redirige_connexion(): void
    {
        $response = $this->get(route('abonnements'));
        $response->assertRedirect(route('connexion'));
    }

    public function test_abonnements_connecte_retourne_200(): void
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('abonnements'));
        $response->assertStatus(200);
        $response->assertViewIs('user.abonnements');
    }

    public function test_suivre_utilisateur_ajoute_relation(): void
    {
        $follower = User::factory()->create();
        $followed = User::factory()->create();
        $this->actingAs($follower);
        $response = $this->post(route('suivre', $followed->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('suivre', [
            'follower_id' => $follower->id,
            'followed_id' => $followed->id,
        ]);
    }
}
