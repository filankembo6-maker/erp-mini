<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TestRoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'commercial', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'magasinier', 'guard_name' => 'web']);
    }
}