<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class S00_TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = new Tenant();
        $tenant->name = "Deposito Tajy";
        $tenant->save();

        $tenant = new Tenant();
        $tenant->name = "Noel Ingenienria";
        $tenant->save();

        $tenant = new Tenant();
        $tenant->name = "Ferreteria San Juan";
        $tenant->save();

        $tenant = new Tenant();
        $tenant->name = "Arquitectura Lineal";
        $tenant->save();

        $tenant = new Tenant();
        $tenant->name = "Ferreteria Don Ale";
        $tenant->save();
    }
}
