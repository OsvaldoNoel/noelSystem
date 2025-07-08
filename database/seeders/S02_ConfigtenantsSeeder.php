<?php

namespace Database\Seeders;

use App\Models\Configtenants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class S02_ConfigtenantsSeeder extends Seeder
{ 
    public function run(): void
    {
        $config = new Configtenants();
            $config->tenant_id = "1";

            $config->bajaLista1 = "10.00";
            $config->bajaLista2 = "20.00";
            $config->bajaLista3 = "30.00";

            $config->mediaLista1 = "25.00";
            $config->mediaLista2 = "35.00"; 
            $config->mediaLista3 = "45.00";

            $config->altaLista1 = "60.00";
            $config->altaLista2 = "80.00";
            $config->altaLista3 = "100.00";

            $config->save();
    }
}
