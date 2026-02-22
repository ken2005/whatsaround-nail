<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DiffusionSeeder::class);
    }

    public function test_page_connexion_retourne_200(): void
    {
        $response = $this->get(route('connexion'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_page_inscription_retourne_200(): void
    {
        $response = $this->get(route('inscription'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.signup');
    }

    public function test_inscription_creer_utilisateur_et_redirige(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertRedirect(route('accueil'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_connexion_avec_bons_identifiants_redirige_accueil(): void
    {
        $user = User::factory()->create(['email' => 'login@test.com']);
        $response = $this->post(route('login'), [
            'email' => 'login@test.com',
            'password' => 'password',
        ]);
        $response->assertRedirect(route('accueil'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_connexion_identifiants_invalides_retourne_erreur(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'inconnu@test.com',
            'password' => 'wrong',
        ]);
        $response->assertSessionHasErrors('email');
        $response->assertStatus(302);
    }
}
