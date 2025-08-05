<?php

namespace App\Http\Requests;

use App\Http\Traits\TenantRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    use TenantRequestTrait;

    // Puedes agregar lógica común aquí
}




// 6. Uso en Controladores
// Ahora puedes usar $request->tenant() en cualquier controlador:
// public function dashboard(Request $request)
// {
//     $tenant = $request->tenant();
    
//     if (!$tenant) {
//         return redirect()->route('select-tenant');
//     }

//     return view('dashboard', [
//         'tenant' => $tenant,
//         'tenantType' => $tenant->typeName
//     ]);
// }