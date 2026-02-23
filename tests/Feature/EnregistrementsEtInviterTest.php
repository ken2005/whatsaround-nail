<?php

namespace Tests\Feature;

use App\Models\Evenement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnregistrementsEtInviterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DiffusionSeeder::class);
    }

    public function test_page_enregistres_sans_connexion_redirige_connexion(): void
    {
        $response = $this->get(route('evenements.enregistres'));
        $response->assertRedirect(route('connexion'));
    }

    public function test_page_enregistres_connecte_retourne_200(): void
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('evenements.enregistres'));
        $response->assertStatus(200);
        $response->assertViewIs('evenement.enregistres');
    }

    public function test_enregistrer_evenement_connecte_ajoute_en_favoris(): void
    {
        $user = User::factory()->create();
        $event = Evenement::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->post(route('enregistrer', $event->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('enregistrer', [
            'user_id' => $user->id,
            'evenement_id' => $event->id,
        ]);
    }

    public function test_inviter_sans_etre_proprio_retourne_403(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $event = Evenement::factory()->create(['user_id' => $owner->id]);
        $this->actingAs($other);
        $response = $this->get(route('evenement.inviter', $event->id));
        $response->assertStatus(403);
    }

    public function test_inviter_en_tant_que_proprio_retourne_200(): void
    {
        $owner = User::factory()->create();
        $event = Evenement::factory()->create(['user_id' => $owner->id]);
        $this->actingAs($owner);
        $response = $this->get(route('evenement.inviter', $event->id));
        $response->assertStatus(200);
        $response->assertViewIs('evenement.inviter');
    }
}
