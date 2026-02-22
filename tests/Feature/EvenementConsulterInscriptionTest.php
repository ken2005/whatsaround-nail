<?php

namespace Tests\Feature;

use App\Models\Evenement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvenementConsulterInscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DiffusionSeeder::class);
    }

    public function test_consulter_evenement_public_retourne_200(): void
    {
        $user = User::factory()->create();
        $event = Evenement::factory()->create([
            'user_id' => $user->id,
            'diffusion_id' => 1,
        ]);
        $response = $this->get(route('evenement', $event->id));
        $response->assertStatus(200);
        $response->assertViewIs('evenement.consulter');
        $response->assertSee($event->nom);
    }

    public function test_consulter_evenement_inexistant_retourne_404(): void
    {
        $response = $this->get(route('evenement', 99999));
        $response->assertStatus(404);
    }

    public function test_inscription_utilisateur_connecte_redirige_et_inscrit(): void
    {
        $user = User::factory()->create();
        $event = Evenement::factory()->create(['user_id' => $user->id, 'diffusion_id' => 1]);
        $participant = User::factory()->create();
        $this->actingAs($participant);
        $response = $this->post(route('sInscrire', $event->id));
        $response->assertRedirect();
        $response->assertSessionHas('message');
        $this->assertDatabaseHas('s_inscrire', [
            'user_id' => $participant->id,
            'evenement_id' => $event->id,
        ]);
    }

    public function test_inscription_sans_connexion_redirige_connexion(): void
    {
        $user = User::factory()->create();
        $event = Evenement::factory()->create(['user_id' => $user->id]);
        $response = $this->post(route('sInscrire', $event->id));
        $response->assertRedirect(route('connexion'));
    }
}
