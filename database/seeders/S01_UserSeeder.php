<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class S01_UserSeeder extends Seeder
{ 
    public function run(): void
    {
        $user = new User(); 
        $user->ci = "3707064";
        $user->username = "3707064";
        $user->name = "Osvaldo Noel";
        $user->lastname = "Benitez Acosta";
        $user->phone = "0981 331 979";
        $user->address = "Gaspar R. de Francia 125";
        $user->barrio = "San Francisco";
        $user->city = "Limpio";
        $user->email = "noel@gmail.com";
        $user->password = '$2y$12$s0GaHxRxU1wmGqESrZoVZO8vBg5gPttoXTjHB9GRivQvtOFRNvV9i';
        $user->save();

        $user = new User();
        $user->tenant_id = "1";
        $user->ci = "3707064";
        $user->username = "37070641";
        $user->name = "Osvaldo Noel";
        $user->lastname = "Benitez Acosta";
        $user->phone = "0981 331 979";
        $user->address = "Gaspar R. de Francia 125";
        $user->barrio = "San Francisco";
        $user->city = "Limpio";
        $user->email = "noel@gmail.com";
        $user->password = '$2y$12$s0GaHxRxU1wmGqESrZoVZO8vBg5gPttoXTjHB9GRivQvtOFRNvV9i';
        $user->save();

        $user = new User(); 
        $user->ci = "4898172";
        $user->username = "4898172";
        $user->name = "Ana Liz";
        $user->lastname = "Moraez MArtinez";
        $user->phone = "0981 888 999";
        $user->address = "Calle Salado";
        $user->barrio = "Salado";
        $user->city = "Limpio";
        $user->email = "ana@gmail.com";
        $user->password = '$2y$12$/NxzAvIP/LJvjfZgyCFcQ.6HU6yrzYaW9fJOSlkGTpK7C2MAyQ5aC';
        $user->save();

        $user = new User();
        $user->tenant_id = "1";
        $user->ci = "4898172";
        $user->username = "48981721";
        $user->name = "Ana Liz";
        $user->lastname = "Moraez MArtinez";
        $user->phone = "0981 888 999";
        $user->address = "Calle Salado";
        $user->barrio = "Salado";
        $user->city = "Limpio";
        $user->email = "ana@gmail.com";
        $user->password = '$2y$12$/NxzAvIP/LJvjfZgyCFcQ.6HU6yrzYaW9fJOSlkGTpK7C2MAyQ5aC';
        $user->save();

        $user = new User();
        $user->tenant_id = "1";
        $user->ci = "4366832";
        $user->username = "43668321";
        $user->name = "Aida Noemi";
        $user->lastname = "Torres Benitez";
        $user->phone = "0981 333 444";
        $user->address = "Calle San Blas";
        $user->barrio = "San Blas";
        $user->city = "Limpio";
        $user->email = "aida@gmail.com";
        $user->password = '$2y$12$c9QriP2/pGpI5gBWHmjiFO.YDKVR3mhi.VXA17/PiGYG7B5f6Eohm';
        $user->save();

        $user = new User();
        $user->tenant_id = "1";
        $user->ci = "6706244";
        $user->username = "67062441";
        $user->name = "Magali";
        $user->lastname = "Gonzalez Moraez";
        $user->phone = "0981 555 999";
        $user->address = "Calle Salado";
        $user->barrio = "Salado";
        $user->city = "Limpio";        
        $user->email = "magali@gmail.com";
        $user->password = '$2y$12$dejdECKcPckKfrrCN0JwseWyw4eGSFXIAqGzTH/0USvOMkGnmaWx.';
        $user->save();

        $user = new User();
        $user->tenant_id = "1";
        $user->ci = "6283281";
        $user->username = "62832811";
        $user->name = "Fatima";
        $user->lastname = "Gonzalez Gonzalez";
        $user->phone = "0981 222 333";
        $user->address = "Camino a piquete";
        $user->barrio = "Rincon del Peñon";
        $user->city = "Limpio";        
        $user->email = "fatima@gmail.com";
        $user->password = '$2y$12$LYww.hW9.i9kUayGXrlSbOXKNWZrcU8Ox1jFMPsNFCzkewuiY3GAi';
        $user->save();

        $user = new User();
        $user->tenant_id = "2";
        $user->ci = "4898172";
        $user->username = "48981722";
        $user->name = "Ana Liz";
        $user->lastname = "Moraez MArtinez";
        $user->phone = "0981 888 999";
        $user->address = "Calle Salado";
        $user->barrio = "Salado";
        $user->city = "Limpio";
        $user->email = "ana@gmail.com";
        $user->password = '$2y$12$/NxzAvIP/LJvjfZgyCFcQ.6HU6yrzYaW9fJOSlkGTpK7C2MAyQ5aC';
        $user->save();

        $user = new User();
        $user->tenant_id = "2";
        $user->ci = "4366832";
        $user->username = "43668322";
        $user->name = "Aida Noemi";
        $user->lastname = "Torres Benitez";
        $user->phone = "0981 333 444";
        $user->address = "Calle San Blas";
        $user->barrio = "San Blas";
        $user->city = "Limpio";
        $user->email = "aida@gmail.com";
        $user->password = '$2y$12$c9QriP2/pGpI5gBWHmjiFO.YDKVR3mhi.VXA17/PiGYG7B5f6Eohm';
        $user->save();

        $user = new User();
        $user->tenant_id = "4";
        $user->ci = "4366832";
        $user->username = "43668324";
        $user->name = "Aida Noemi";
        $user->lastname = "Torres Benitez";
        $user->phone = "0981 333 444";
        $user->address = "Calle San Blas";
        $user->barrio = "San Blas";
        $user->city = "Limpio";
        $user->email = "aida@gmail.com";
        $user->password = '$2y$12$c9QriP2/pGpI5gBWHmjiFO.YDKVR3mhi.VXA17/PiGYG7B5f6Eohm';
        $user->save();
    }
}
