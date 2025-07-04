<?php

namespace Database\Seeders;

use App\Models\Presentation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class S03_PresentationSeeder extends Seeder
{ 
    public function run(): void
    {
        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "balde 18 lt";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "lata 3,6 lt";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "lata 1 lt";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "lata 900cc";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "bolsa 50kg";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "bolsa 20kg";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "bolsa 5kg";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "tira 12m";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "tira 6m";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "tira 3m";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "tira 2m";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "tira 1m";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "carga";
        $presentation->save();

        $presentation = new Presentation();
        $presentation->tenant_id = "1";
        $presentation->name = "1/2 carga";
        $presentation->save(); 
    }
}
