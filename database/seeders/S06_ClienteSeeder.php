<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class S06_ClienteSeeder extends Seeder
{
    
    public function run(): void
    {
        $cliente = new Cliente();
            $cliente->tenant_id = "1";
            $cliente->name = "Osvaldo Noel Benitez Acosta";
            $cliente->ruc = "3707064";
            $cliente->dv = "9";
            $cliente->barrio = "San Francisco";
            $cliente->city = "Limpio";
            $cliente->adress = "Gaspar Rodiguez de Francia - Limpio";
            $cliente->phone = "0981 331 979";
            $cliente->email = "noel@gmail.com";
            $cliente->status = "1";
            $cliente->save();

        $cliente = new Cliente();
            $cliente->tenant_id = "1";
            $cliente->name = "Aida Noemi Torres Benitez";
            $cliente->ruc = "4568952";
            $cliente->dv = "3";
            $cliente->barrio = "Colonia Juan de Zalazar";
            $cliente->city = "Limpio";
            $cliente->adress = "Colonia Juan de Zalazar - Limpio";
            $cliente->phone = "0961 325 689";
            $cliente->email = "aida@gmail.com";
            $cliente->status = "1";
            $cliente->save();

        $cliente = new Cliente();
            $cliente->tenant_id = "1";
            $cliente->name = "Ana Liz Moraez";
            $cliente->ruc = "4872361";
            $cliente->dv = "3";
            $cliente->barrio = "San Geronimo - Salado";
            $cliente->city = "limpio";
            $cliente->adress = "Limpio";
            $cliente->phone = "0993 658 984";
            $cliente->email = "ana@gmail.com";
            $cliente->status = "1";
            $cliente->save();

        Cliente::factory(1000)->create();
    }
}
