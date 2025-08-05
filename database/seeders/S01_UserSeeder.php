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
        $user->user_profile_id = "1";
        $user->username = "3707064";
        $user->email = "noel@gmail.com";
        $user->password = '$2y$12$s0GaHxRxU1wmGqESrZoVZO8vBg5gPttoXTjHB9GRivQvtOFRNvV9i';
        $user->save();

        // $user = new User();
        // $user->user_profile_id = "1";
        // $user->tenant_id = "1";
        // $user->username = "37070641";
        // $user->email = "noel@gmail.com";
        // $user->password = '$2y$12$s0GaHxRxU1wmGqESrZoVZO8vBg5gPttoXTjHB9GRivQvtOFRNvV9i';
        // $user->save();

        $user = new User(); 
        $user->user_profile_id = "2";
        $user->username = "4898172";
        $user->email = "ana@gmail.com";
        $user->password = '$2y$12$/NxzAvIP/LJvjfZgyCFcQ.6HU6yrzYaW9fJOSlkGTpK7C2MAyQ5aC';
        $user->save();

        // $user = new User();
        // $user->user_profile_id = "2";
        // $user->tenant_id = "1";
        // $user->username = "48981721";
        // $user->email = "ana@gmail.com";
        // $user->password = '$2y$12$/NxzAvIP/LJvjfZgyCFcQ.6HU6yrzYaW9fJOSlkGTpK7C2MAyQ5aC';
        // $user->save();

        // $user = new User();
        // $user->user_profile_id = "3";
        // $user->tenant_id = "1";
        // $user->username = "43668321";
        // $user->email = "aida@gmail.com";
        // $user->password = '$2y$12$c9QriP2/pGpI5gBWHmjiFO.YDKVR3mhi.VXA17/PiGYG7B5f6Eohm';
        // $user->save();

        // $user = new User();
        // $user->user_profile_id = "4";
        // $user->tenant_id = "1";
        // $user->username = "67062441";
        // $user->email = "magali@gmail.com";
        // $user->password = '$2y$12$dejdECKcPckKfrrCN0JwseWyw4eGSFXIAqGzTH/0USvOMkGnmaWx.';
        // $user->save();

        // $user = new User();
        // $user->user_profile_id = "5";
        // $user->tenant_id = "5";
        // $user->username = "62832811";    
        // $user->email = "fatima@gmail.com";
        // $user->password = '$2y$12$LYww.hW9.i9kUayGXrlSbOXKNWZrcU8Ox1jFMPsNFCzkewuiY3GAi';
        // $user->save();

        // $user = new User();
        // $user->user_profile_id = "2";
        // $user->tenant_id = "2";
        // $user->username = "48981722";
        // $user->email = "ana@gmail.com";
        // $user->password = '$2y$12$/NxzAvIP/LJvjfZgyCFcQ.6HU6yrzYaW9fJOSlkGTpK7C2MAyQ5aC';
        // $user->save();

        // $user = new User();
        // $user->user_profile_id = "3";
        // $user->tenant_id = "2";
        // $user->username = "43668322";
        // $user->email = "aida@gmail.com";
        // $user->password = '$2y$12$c9QriP2/pGpI5gBWHmjiFO.YDKVR3mhi.VXA17/PiGYG7B5f6Eohm';
        // $user->save();

        // $user = new User();
        // $user->user_profile_id = "3";
        // $user->tenant_id = "4";
        // $user->username = "43668324";
        // $user->email = "aida@gmail.com";
        // $user->password = '$2y$12$c9QriP2/pGpI5gBWHmjiFO.YDKVR3mhi.VXA17/PiGYG7B5f6Eohm';
        // $user->save();

        // $user = new User();
        // $user->user_profile_id = "1";
        // $user->tenant_id = "2";
        // $user->username = "37070642";
        // $user->email = "noel@gmail.com";
        // $user->password = '$2y$12$s0GaHxRxU1wmGqESrZoVZO8vBg5gPttoXTjHB9GRivQvtOFRNvV9i';
        // $user->save();

        // $user = new User();
        // $user->user_profile_id = "1";
        // $user->tenant_id = "5";
        // $user->username = "37070645";
        // $user->email = "noel@gmail.com";
        // $user->password = '$2y$12$s0GaHxRxU1wmGqESrZoVZO8vBg5gPttoXTjHB9GRivQvtOFRNvV9i';
        // $user->save();
    }
}
