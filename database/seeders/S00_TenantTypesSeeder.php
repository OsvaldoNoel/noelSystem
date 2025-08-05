<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class S00_TenantTypesSeeder extends Seeder
{
    public function run(): void
    {
        // $types = [
        //     ['id' => Tenant::TYPE_POS, 'name' => 'POS'],
        //     ['id' => Tenant::TYPE_SERVICIOS, 'name' => 'Servicios'],
        //     ['id' => Tenant::TYPE_MICROVENTAS, 'name' => 'MicroVentas'],
        //     ['id' => Tenant::TYPE_RESTAURANTE, 'name' => 'Restaurante'],
        // ];

        // foreach ($types as $type) {
        //     Tenant::updateOrCreate(
        //         ['tenant_type' => $type['id']],
        //         ['name' => $type['name']]
        //     );
        // }
    }
}