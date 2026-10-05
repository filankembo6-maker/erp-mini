<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'commercial', 'guard_name' => 'web']);
        Role::create(['name' => 'magasinier', 'guard_name' => 'web']);
    }

    public function test_admin_peut_voir_la_liste_des_produits()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Product::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('/products');

        $response->assertStatus(200);
    }

    public function test_admin_peut_creer_un_produit()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post('/products', [
            'name' => 'Souris sans fil',
            'sku' => 'SOU-001',
            'description' => 'Souris ergonomique',
            'price' => 29.99,
            'stock_quantity' => 50,
            'stock_alert' => 5,
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['sku' => 'SOU-001']);
    }

    public function test_magasinier_ne_peut_pas_acceder_aux_clients()
    {
        $magasinier = User::factory()->create();
        $magasinier->assignRole('magasinier');

        $response = $this->actingAs($magasinier)->get('/clients');

        $response->assertStatus(403);
    }

    public function test_utilisateur_non_authentifie_est_redirige()
    {
        $response = $this->get('/products');

        $response->assertRedirect('/login');
    }
}