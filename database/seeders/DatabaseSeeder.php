<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(S00_TenantSeeder::class);
        $this->call(S01_ConfigtenantsSeeder::class);
        $this->call(S01_UserSeeder::class);
        $this->call(S02_MarcaSeeder::class);
        $this->call(S03_PresentationSeeder::class);
        $this->call(S04_CategoriaSeeder::class);
        $this->call(S05_SubcategoriaSeeder::class);
        $this->call(S06_ClienteSeeder::class);
        $this->call(S07_ProveedorSeeder::class);
        $this->call(S08_ProductoSeeder::class);
    }
}
