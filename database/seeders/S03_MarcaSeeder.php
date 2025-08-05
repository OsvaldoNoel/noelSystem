<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class S03_MarcaSeeder extends Seeder
{ 
    public function run(): void
    {
        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Tramontina";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Makita";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Vallemi";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Yguazu";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Titan";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Tigre";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Acepar";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Cecon";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Concrecal";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Inatec";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Cerecita";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Klaucol";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Claumix";
            $marca->save();

        $marca = new Marca();
            $marca->tenant_id = "1";
            $marca->name = "Botomasa";
            $marca->save(); 
    }
}
