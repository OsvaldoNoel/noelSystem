<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class S07_ProveedorSeeder extends Seeder
{
    
    public function run(): void
    {
        $proveedor = new Proveedor();
            $proveedor->tenant_id = "1";
            $proveedor->name = "Lincoln";
            $proveedor->ruc = "800254348";
            $proveedor->dv = "3";
            $proveedor->barrio = "";
            $proveedor->city = "Luque";
            $proveedor->adress = "Ruta Gral aquino";
            $proveedor->phone = "021 645 892";
            $proveedor->email = "lincoln@gmail.com";
            $proveedor->status = "1";
            $proveedor->save();

        $proveedor = new Proveedor();
            $proveedor->tenant_id = "1";
            $proveedor->name = "Distribuidora Gallo";
            $proveedor->ruc = "800036176";
            $proveedor->dv = "5";
            $proveedor->barrio = "";
            $proveedor->city = "Capiata";
            $proveedor->adress = "Ruta Mcal Estigarribia";
            $proveedor->phone = "021 489 637";
            $proveedor->email = "lincoln@gmail.com";
            $proveedor->status = "1";
            $proveedor->save();

        $proveedor = new Proveedor();
            $proveedor->tenant_id = "1";
            $proveedor->name = "Atlantic";
            $proveedor->ruc = "800487361";
            $proveedor->dv = "2";
            $proveedor->barrio = "";
            $proveedor->city = "Asuncion";
            $proveedor->adress = "Avda de la Victoria";
            $proveedor->phone = "021 368 892";
            $proveedor->email = "atlantic@gmail.com";
            $proveedor->status = "1";
            $proveedor->save();

        Proveedor::factory(1000)->create();
    }
}
