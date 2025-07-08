<?php

namespace Database\Seeders;

use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class S01_UserProfileSeeder extends Seeder
{ 
    public function run(): void
    {
        $user = new UserProfile(); 
        $user->ci = "3707064";
        $user->name = "Osvaldo Noel";
        $user->lastname = "Benitez Acosta";
        $user->phone = "0981 331 979";
        $user->address = "Gaspar R. de Francia 125";
        $user->barrio = "San Francisco";
        $user->city = "Limpio";
        $user->save();

        $user = new UserProfile(); 
        $user->ci = "4898172";
        $user->name = "Ana Liz";
        $user->lastname = "Moraez MArtinez";
        $user->phone = "0981 888 999";
        $user->address = "Calle Salado";
        $user->barrio = "Salado";
        $user->city = "Limpio";
        $user->save();

        $user = new UserProfile();
        $user->ci = "4366832";
        $user->name = "Aida Noemi";
        $user->lastname = "Torres Benitez";
        $user->phone = "0981 333 444";
        $user->address = "Calle San Blas";
        $user->barrio = "San Blas";
        $user->city = "Limpio";
        $user->save();

        $user = new UserProfile();
        $user->ci = "6706244";
        $user->name = "Magali";
        $user->lastname = "Gonzalez Moraez";
        $user->phone = "0981 555 999";
        $user->address = "Calle Salado";
        $user->barrio = "Salado";
        $user->city = "Limpio";        
        $user->save();

        $user = new UserProfile();
        $user->ci = "6283281";
        $user->name = "Fatima";
        $user->lastname = "Gonzalez Gonzalez";
        $user->phone = "0981 222 333";
        $user->address = "Camino a piquete";
        $user->barrio = "Rincon del Peñon";
        $user->city = "Limpio";        
        $user->save();

    }
}
