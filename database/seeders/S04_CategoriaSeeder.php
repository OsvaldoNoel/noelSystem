<?php

namespace Database\Seeders;

use App\Models\categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class S04_CategoriaSeeder extends Seeder
{ 
    public function run(): void
    {
        $categoria = new categoria();
        $categoria->tenant_id = "1";    
        $categoria->name = "Albañileria";
        $categoria->save();

        $categoria = new Categoria();
        $categoria->tenant_id = "1";    
        $categoria->name = "Herreria";
        $categoria->save();

        $categoria = new Categoria();
        $categoria->tenant_id = "1";    
        $categoria->name = "Electricidad";
        $categoria->save();

        $categoria = new Categoria();
        $categoria->tenant_id = "1";    
        $categoria->name = "Ferreteria";
        $categoria->save();

        $categoria = new Categoria();
        $categoria->tenant_id = "1";    
        $categoria->name = "Plomeria";
        $categoria->save();

        $categoria = new Categoria();
        $categoria->tenant_id = "1";    
        $categoria->name = "Refrigeración";
        $categoria->save();

        $categoria = new Categoria();
        $categoria->tenant_id = "1";    
        $categoria->name = "Herramientas";
        $categoria->save();

        $categoria = new Categoria();
        $categoria->tenant_id = "1";    
        $categoria->name = "Jardinería";
        $categoria->save();
    }
}
