<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'commercial', 'guard_name' => 'web']);
        Role::create(['name' => 'magasinier', 'guard_name' => 'web']);
    }

    public function test_commercial_peut_creer_un_client()
    {
        $commercial = User::factory()->create();
        $commercial->assignRole('commercial');

        $response = $this->actingAs($commercial)->post('/clients', [
            'name' => 'Jean Dupont',
            'email' => 'jean@test.com',
            'phone' => '0612345678',
            'city' => 'Brazzaville',
        ]);

        $response->assertRedirect('/clients');
        $this->assertDatabaseHas('clients', ['email' => 'jean@test.com']);
    }

    public function test_magasinier_ne_peut_pas_creer_un_client()
    {
        $magasinier = User::factory()->create();
        $magasinier->assignRole('magasinier');

        $response = $this->actingAs($magasinier)->post('/clients', [
            'name' => 'Test',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(403);
    }
}