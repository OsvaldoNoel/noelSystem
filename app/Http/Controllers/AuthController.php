<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;

class AuthController extends Controller
{
    public function validateCi(Request $request)
    {
        $request->validate([
            'ci' => 'required|string|min:3'
        ]);

        $user = User::where('ci', $request->ci)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no registrado'
            ]);
        }

        // Obtener tenants asociados al usuario
        $tenants = [];
        
        if ($user->tenant_id) {
            // Usuario normal (un tenant)
            $tenants[] = $user->tenant;
        } else {
            // Landlord (todos los tenants)
            $tenants = Tenant::all();
        }

        return response()->json([
            'success' => true,
            'tenants' => $tenants->map(function($tenant) {
                return [
                    'id' => $tenant->id,
                    'name' => $tenant->name
                ];
            })
        ]);
    }
}