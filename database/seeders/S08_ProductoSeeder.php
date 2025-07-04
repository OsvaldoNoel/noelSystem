<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class S08_ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $producto = new Product();
            $producto->tenant_id = "1";
            $producto->code = "1031";
            $producto->name = "Ladrillo Comun";
            $producto->costoPromedio = "550";
            $producto->priceList1= "700";
            $producto->priceList2= "700";
            $producto->priceList3= "700";
            $producto->stock = "6200";
            $producto->stockmin = "5000";
            $producto->stockmax = "25000";
            $producto->status = "1"; 
            $producto->categoria_id = "1";
            $producto->subcategoria_id = "1";
            $producto->marca_id = "1";
            $producto->presentation_id = "1";
            $producto->save();

        $producto = new Product();
            $producto->tenant_id = "1";
            $producto->code = "1032";
            $producto->name = "Triturada 4ta";
            $producto->costoPromedio = "110000";
            $producto->priceList1= "150000";
            $producto->priceList2= "150000";
            $producto->priceList3= "150000";
            $producto->stock = "7";
            $producto->stockmin = "5";
            $producto->stockmax = "10";
            $producto->status = "1"; 
            $producto->categoria_id = "1";
            $producto->subcategoria_id = "2";
            $producto->marca_id = "3";
            $producto->presentation_id = "2";
            $producto->save();
            
        $producto = new Product();
            $producto->tenant_id = "1";
            $producto->code = "1033";
            $producto->name = "Cemento";
            $producto->costoPromedio = "55000";
            $producto->priceList1= "62000";
            $producto->priceList2= "62000";
            $producto->priceList3= "62000";
            $producto->stock = "75";
            $producto->stockmin = "50";
            $producto->stockmax = "100";
            $producto->status = "1"; 
            $producto->categoria_id = "1";
            $producto->subcategoria_id = "2";
            $producto->marca_id = "2";
            $producto->presentation_id = "2";
            $producto->save();

        $producto = new Product();
            $producto->tenant_id = "1";
            $producto->code = "1034";
            $producto->name = "tubo PVC 100mm";
            $producto->costoPromedio = "42000";
            $producto->priceList1= "55000";
            $producto->priceList2= "55000";
            $producto->priceList3= "55000";
            $producto->stock = "17";
            $producto->stockmin = "20";
            $producto->stockmax = "50";
            $producto->status = "1"; 
            $producto->categoria_id = "3";
            $producto->subcategoria_id = "3";
            $producto->marca_id = "5";
            $producto->presentation_id = "5";
            $producto->save();

        $producto = new Product();
            $producto->tenant_id = "1";
            $producto->code = "1035";
            $producto->name = "Varilla de 8mm";
            $producto->costoPromedio = "32000";
            $producto->priceList1= "40000";
            $producto->priceList2= "40000";
            $producto->priceList3= "40000";
            $producto->stock = "27";
            $producto->stockmin = "20";
            $producto->stockmax = "40";
            $producto->status = "1"; 
            $producto->categoria_id = "5";
            $producto->subcategoria_id = "2";
            $producto->marca_id = "4";
            $producto->presentation_id = "3";
            $producto->save();

        $producto = new Product();
            $producto->tenant_id = "1";
            $producto->code = "1036";
            $producto->name = "Cajas de Conexion";
            $producto->costoPromedio = "1100";
            $producto->priceList1= "2200";
            $producto->priceList2= "2200";
            $producto->priceList3= "2200";
            $producto->stock = "96";
            $producto->stockmin = "5";
            $producto->stockmax = "10";
            $producto->status = "1"; 
            $producto->categoria_id = "2";
            $producto->subcategoria_id = "4";
            $producto->marca_id = "3";
            $producto->presentation_id = "5";
            $producto->save();

    }
}
