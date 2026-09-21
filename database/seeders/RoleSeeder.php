<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'voir produits', 'creer produits', 'modifier produits', 'supprimer produits',
            'voir clients', 'creer clients', 'modifier clients', 'supprimer clients',
            'voir stocks', 'gerer stocks',
            'voir devis', 'creer devis', 'modifier devis', 'supprimer devis',
            'voir factures', 'creer factures', 'modifier factures',
            'voir logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        $commercial = Role::firstOrCreate(['name' => 'commercial']);
        $commercial->syncPermissions([
            'voir produits',
            'voir clients', 'creer clients', 'modifier clients',
            'voir devis', 'creer devis', 'modifier devis',
            'voir factures', 'creer factures',
        ]);

        $magasinier = Role::firstOrCreate(['name' => 'magasinier']);
        $magasinier->syncPermissions([
            'voir produits', 'creer produits', 'modifier produits',
            'voir stocks', 'gerer stocks',
        ]);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@erp.com'],
            ['name' => 'Admin ERP', 'password' => bcrypt('password')]
        );
        $adminUser->syncRoles([$admin]);

        $commercialUser = User::firstOrCreate(
            ['email' => 'commercial@erp.com'],
            ['name' => 'Jean Commercial', 'password' => bcrypt('password')]
        );
        $commercialUser->syncRoles([$commercial]);

        $magasinierUser = User::firstOrCreate(
            ['email' => 'magasinier@erp.com'],
            ['name' => 'Marie Magasin', 'password' => bcrypt('password')]
        );
        $magasinierUser->syncRoles([$magasinier]);
    }
}