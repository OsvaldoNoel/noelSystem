<?php

namespace Database\Seeders;

use App\Models\Subcategoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class S05_SubcategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Aridos";
        $subcategoria->categoria_id = "1";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Ceramicos";
        $subcategoria->categoria_id = "1";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Aglomerantes";
        $subcategoria->categoria_id = "1";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Pico punto";
        $subcategoria->categoria_id = "3";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Pico toma";
        $subcategoria->categoria_id = "3";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Placas";
        $subcategoria->categoria_id = "3";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Focos";
        $subcategoria->categoria_id = "3";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Cables";
        $subcategoria->categoria_id = "3";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Buloneria";
        $subcategoria->categoria_id = "4";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Tarugos";
        $subcategoria->categoria_id = "4";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Construcciones";
        $subcategoria->categoria_id = "4";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "De mano";
        $subcategoria->categoria_id = "7";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Electricos";
        $subcategoria->categoria_id = "7";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Chapas";
        $subcategoria->categoria_id = "2";
        $subcategoria->save(); 

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Perfiles";
        $subcategoria->categoria_id = "2";
        $subcategoria->save(); 

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Tubos";
        $subcategoria->categoria_id = "2";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Mangueras";
        $subcategoria->categoria_id = "8";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Accesorios";
        $subcategoria->categoria_id = "8";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Canillas";
        $subcategoria->categoria_id = "5";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Codos y conexiones";
        $subcategoria->categoria_id = "5";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Caños";
        $subcategoria->categoria_id = "5";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Soporte";
        $subcategoria->categoria_id = "6";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Capacitores";
        $subcategoria->categoria_id = "6";
        $subcategoria->save();

        $subcategoria = new Subcategoria();
        $subcategoria->tenant_id = "1";    
        $subcategoria->name = "Compresores";
        $subcategoria->categoria_id = "6";
        $subcategoria->save();
    }
}
