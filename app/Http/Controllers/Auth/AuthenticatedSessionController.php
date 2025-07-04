<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Valida el CI y devuelve los tenants asociados
     */
    public function validateCi(Request $request): JsonResponse
    {
        $request->validate(['ci' => 'required|string|min:3']);

        // Busqueda exacta del CI (sin coincidencias parciales)
        $users = User::where('ci', $request->ci)->get();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no registrado'
            ], 404);
        }

        $options = [];

        // 1. Agregar Landlord primero si existe
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

            $user = Auth::user();

            // Guardar tipo de usuario en sesión
            session(['user_type' => $user->tenant_id ? 'tenant' : 'landlord']);

            // Redirección basada en tipo de usuario
            $redirectRoute = $user->tenant_id ? 'homeApp' : 'homeLandlord';

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
