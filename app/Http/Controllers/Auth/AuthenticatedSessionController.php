<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    public function create(): View
    {
        return view('auth.login');
    }

    //Valida el CI y devuelve los tenants asociados
    public function validateCi(Request $request): JsonResponse
    {
        $request->validate(['ci' => 'required|string|min:3']);

        // Buscar el perfil con el CI
        $profile = userProfile::with('users')->where('ci', $request->ci)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no registrado'
            ], 404);
        }

        $options = [];
        $users = $profile->users;

        // 1. Agregar Landlord primero si existe un tenant_id nulo
        if ($users->contains('tenant_id', null)) {
            $options[] = [
                'id' => 'landlord',
                'name' => '🔑 Landlord (Acceso Administrativo)'
            ];
        }

        // 2. Agregar tenants normales
        $tenantIds = $users->pluck('tenant_id')->unique()->filter();
        if ($tenantIds->isNotEmpty()) {
            $tenants = Tenant::whereIn('id', $tenantIds)->get();
            foreach ($tenants as $tenant) {
                $options[] = [
                    'id' => $tenant->id,
                    'name' => '🏢 ' . $tenant->name
                ];
            }
        }

        return response()->json([
            'success' => true,
            'tenants' => $options
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            /** @var User $user */ // Esto ayuda al IDE a reconocer el tipo
            $user = Auth::user();
            $tenant = $user->tenant_id ? Tenant::find($user->tenant_id) : null;

            // Determinar user_type y nombres
            if ($user->tenant_id === null) {
                // Usuario landlord (administrador central)
                $userType = 'landlord';
                $tenantName = null;
                $sucursalName = null;
            } else {
                // Usuario relacionado a un tenant
                $tenantName = $tenant->name;
                $userType = 'tenant';
                // 1. Determinar si el tenant del usuario es "casa central" de otras sucursales
                $tiene_sucursales = Tenant::where('sucursal', $user->tenant_id)->exists()
                    ? true
                    : false;

                if ($tiene_sucursales) {
                    if ($user->sucursal === null) {
                        // Usuario sin sucursal asignada
                        $sucursalName = "Casa Central";
                    } else {
                        // Usuario asignado a una sucursal específica
                        $sucursalName = Tenant::find($user->sucursal)->name;
                    }
                } else {
                    $sucursalName = null;
                }
            }

            // Guardar información en sesión
            session([
                'user_type' => $userType,
                'tenant' => $tenantName,
                'sucursal' => $sucursalName,
                'tenant_id' => $user->tenant_id,
                'sucursal_id' => $user->sucursal,
                'user_id' => $user->id // Agregar user_id para referencia
            ]);

            // Agregar el tenant al request para que esté disponible inmediatamente
            $request->merge(['tenant' => $tenant]);

            // Redirección basada en tipo de usuario
            $redirectRoute = $userType === 'tenant' ? 'homeApp' : 'homeLandlord';

            return response()->json([
                'redirect' => route($redirectRoute)
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], $e->status);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
