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
    // Usamos las constantes del modelo User para mantener consistencia
    private const REDIRECT_LANDLORD = 'landlord.home';
    private const REDIRECT_TENANT = 'app.home';

    /**
     * Muestra el formulario de login
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Valida el CI y devuelve los tenants asociados
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function validateCi(Request $request): JsonResponse
    {
        $request->validate(['ci' => 'required|string|min:3']);

        $profile = UserProfile::with('users')->where('ci', $request->ci)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no registrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'tenants' => $this->formatTenantOptions($profile->users)
        ]);
    }

    /**
     * Maneja una solicitud de autenticación
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function store(LoginRequest $request): JsonResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            $user = Auth::user();
            $this->setupUserSession($request, $user);

            return response()->json([
                'redirect' => route($this->getRedirectRoute($user))
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], $e->status);
        }
    }

    /**
     * Formatea las opciones de tenant para la respuesta JSON
     *
     * @param \Illuminate\Database\Eloquent\Collection $users
     * @return array
     */
    protected function formatTenantOptions($users): array
    {
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

        return $options;
    }

    /**
     * Configura la sesión del usuario después del login
     *
     * @param Request $request
     * @param User $user
     * @return void
     */
    protected function setupUserSession(Request $request, User $user): void
    {
        $sessionData = $this->determineSessionData($user);
        $request->session()->put($sessionData);
        $request->merge(['tenant' => $sessionData['tenant']]);
    }

    /**
     * Determina los datos de sesión basados en el tipo de usuario
     *
     * @param User $user
     * @return array
     */
    protected function determineSessionData(User $user): array
    {
        if ($user->isLandlord()) {
            return [
                'user_type' => User::TYPE_LANDLORD, // Usamos la constante del modelo User
                'tenant' => null,
                'sucursal' => null,
                'tenant_id' => null,
                'sucursal_id' => null,
                'user_id' => $user->id
            ];
        }

        return $this->getTenantSessionData($user);
    }

    /**
     * Obtiene los datos de sesión para usuarios tenant
     *
     * @param User $user
     * @return array
     */
    protected function getTenantSessionData(User $user): array
    {
        $tenant = $user->tenant;
        $hasBranches = Tenant::where('sucursal', $user->tenant_id)->exists();

        return [
            'user_type' => User::TYPE_TENANT, // Usamos la constante del modelo User
            'tenant' => $tenant->name,
            'sucursal' => $hasBranches ? $this->getBranchName($user) : null,
            'tenant_id' => $user->tenant_id,
            'sucursal_id' => $user->sucursal,
            'user_id' => $user->id
        ];
    }

    /**
     * Obtiene el nombre de la sucursal para mostrar en sesión
     *
     * @param User $user
     * @return string|null
     */
    protected function getBranchName(User $user): ?string
    {
        return $user->sucursal ? 
            Tenant::find($user->sucursal)->name : 
            "Casa Central";
    }

    /**
     * Determina la ruta de redirección post-login
     *
     * @param User $user
     * @return string
     */
    protected function getRedirectRoute(User $user): string
    {
        return $user->isLandlord() ? 
            self::REDIRECT_LANDLORD : 
            self::REDIRECT_TENANT;
    }

    /**
     * Cierra la sesión del usuario
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}