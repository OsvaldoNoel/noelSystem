<?php

namespace App\Livewire\Tenant\Config\Users\List;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class UserListController extends Component
{
    public $selected_id, $username, $ci, $name, $lastname, $phone, $address, $barrio, $city, $email;
    public $componentName = 'Usuario';
    public $datos;
    public $profileFound = false;

    //variable para manejar los roles
    public $availableRoles = [];
    public $userRoles = [];
    public $editingUserId = null;

    public $errorMessages = [
        'create' => 'Error al crear usuario',
        'update' => 'Error al actualizar usuario',
        'delete' => 'Error al eliminar usuario'
    ];

    public function mount()
    {
        $this->updateTable();
        $this->loadRoles();
    }

    public function updateTable()
    {
        $this->datos = User::query()
            ->with('profile')
            ->where('tenant_id', Auth::user()->tenant_id)
            ->select(['id', 'email', 'status', 'user_profile_id'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'ci' => $user->profile->ci,
                    'name' => $user->profile->name,
                    'lastname' => $user->profile->lastname,
                    'phone' => $user->profile->phone,
                    'email' => $user->email,
                    'barrio' => $user->profile->barrio,
                    'city' => $user->profile->city,
                    'address' => $user->profile->address,
                    'status' => $user->status
                ];
            });

        $this->dispatch('tableUpdated', datos: $this->datos);
    }

    #[On('searchProfile')]
    public function searchProfile($ci)
    {
        $this->ci = $ci;

        // Validación inicial del CI
        $this->validateOnly('ci', [
            'ci' => [
                'numeric',
                'min:100000',
                'max:999999999999999',
            ]
        ], [
            'ci.numeric' => 'La cédula debe ser numérica',
            'ci.min' => 'Debe tener al menos 6 dígitos',
            'ci.max' => 'Debe tener máximo 15 dígitos',
        ]);

        // Buscar el perfil en la base de datos
        $profile = UserProfile::where('ci', $this->ci)->first();

        if ($profile) {
            $this->profileFound = true;
            $this->name = $profile->name;
            $this->lastname = $profile->lastname;
            $this->phone = $profile->phone;
            $this->address = $profile->address;
            $this->barrio = $profile->barrio;
            $this->city = $profile->city;
            $this->email = null;

            $this->dispatch('profileFound');
        } else {
            $this->profileFound = false;
            $this->name = null;
            $this->lastname = null;
            $this->phone = null;
            $this->address = null;
            $this->barrio = null;
            $this->city = null;
            $this->email = null;

            $this->dispatch('profileNotFound');
        }
    }

    public function loadRoles()
    {
        $this->availableRoles = Role::where('tenant_id', Auth::user()->tenant_id)
            ->where('name', '!=', 'Propietario')
            ->get()
            ->pluck('name')
            ->toArray();
    }

    public function editUserRoles($userId)
    {
        $this->editingUserId = $userId;
        $user = User::find($userId);
        $this->userRoles = $user->getRoles()->pluck('name')->toArray();
    }

    public function updateUserRoles()
    {
        $user = User::find($this->editingUserId);

        if ($user->hasRole('Propietario', $user->tenant_id)) {
            $this->dispatch('notify', 'No puedes modificar los roles del Propietario', 'error');
            return;
        }

        $user->syncRoles(array_map(function ($role) use ($user) {
            return ['role' => $role, 'tenant_id' => $user->tenant_id];
        }, $this->userRoles));

        $this->editingUserId = null;
        $this->dispatch('notify', 'Roles actualizados');
        $this->dispatch('closeModal');
    }

    #[On('statusUsuario')]
    public function status($id)
    {
        $record = User::findOrFail($id);
        ($record->status == 1) ? $record->update(['status' => 0]) : $record->update(['status' => 1]);
    }

    public function cancel()
    {
        $this->reset([
            'selected_id',
            'username',
            'ci',
            'name',
            'lastname',
            'phone',
            'email',
            'address',
            'barrio',
            'city',
            'profileFound'
        ]);
        $this->resetErrors();
    }

    public function resetErrors()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected $messages = [
        'ci.required' => 'El Nro. es requerido',
        'ci.integer' => 'Debe ser un numero entero',
        'ci.min' => 'Minimo 6 caracteres',
        'ci.max' => 'Como maximo 20 caracteres',
        'username.unique' => 'Este usuario ya fue registrado',
        'name.required' => 'El nombre es requerido',
        'name.min' => 'Minimo 3 caracteres',
        'name.max' => 'Maximo 50 caracteres',
        'lastname.required' => 'El apellido es requerido',
        'lastname.min' => 'Minimo 3 caracteres',
        'lastname.max' => 'Maximo 50 caracteres',
        'phone.required' => 'El telefono es requerido',
        'phone.min' => 'Minimo 9 caracteres',
        'phone.max' => 'Maximo 50 caracteres',
        'email.required' => 'El email es requerido',
        'email.email' => 'El formato es invalido',
        'email.unique' => 'Este email ya fue registrado',
        'address.required' => 'La direccion es requerida',
        'address.min' => 'Minimo 5 caracteres',
        'address.max' => 'Maximo 100 caracteres',
        'barrio.required' => 'El barrio es requerido',
        'barrio.min' => 'Minimo 5 caracteres',
        'barrio.max' => 'Maximo 50 caracteres',
        'city.required' => 'La ciudad es requerida',
        'city.min' => 'Minimo 5 caracteres',
        'city.max' => 'Maximo 50 caracteres',
    ];

    public function store()
    {
        $this->username = $this->ci . Auth::user()->tenant_id;

        try {
            // Validación de campos
            $this->validate([
                'name' => ['required', 'min:3', 'max:50'],
                'ci' => [
                    'min:6',
                    'max:20',
                    'required',
                    Rule::unique('user_profiles', 'ci')->when($this->profileFound, function ($rule) {
                        return $rule->ignore(UserProfile::where('ci', $this->ci)->first()->id);
                    })
                ],
                'username' => ['unique:users'],
                'lastname' => ['required', 'min:3', 'max:50'],
                'phone' => ['required', 'min:6', 'max:50'],
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->where(function ($query) {
                        $query->where('tenant_id', Auth::user()->tenant_id);
                    })
                ],
                'barrio' => ['required', 'min:5', 'max:50'],
                'city' => ['required', 'min:5', 'max:50'],
                'address' => ['required', 'min:5', 'max:100'],
            ]);

            DB::beginTransaction();

            // Primero crear el perfil si no existe
            $profile = UserProfile::firstOrCreate(
                ['ci' => $this->ci],
                [
                    'name' => $this->name,
                    'lastname' => $this->lastname,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'barrio' => $this->barrio,
                    'city' => $this->city
                ]
            );

            // Crear el usuario asociado al tenant
            $user = User::create([
                'tenant_id' => Auth::user()->tenant_id,
                'user_profile_id' => $profile->id,
                'username' => $this->username,
                'email' => $this->email,
                'password' => bcrypt('12345678'),
                'password_changed_at' => null
            ]);

            // Asignar rol por defecto (Ventas) - Versión corregida
            $ventasRole = Role::where('name', 'Ventas')
                ->whereNull('tenant_id') // Solo el rol global
                ->first();
            if (!$ventasRole) {
                throw new \Exception("El rol 'Ventas' no existe en el sistema");
            }
            $user->assignRole($ventasRole, Auth::user()->tenant_id);

            DB::commit();

            $this->dispatch('closeModal');
            $this->dispatch('registroExitoso', text: 'Usuario creado con rol Ventas', bg: 'success');
            $this->dispatch('loadUsers');
            $this->updateTable();
            $this->cancel();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar específicamente la excepción de validación
            $hasCiError = $e->validator->errors()->has('ci');
            $this->dispatch('validationError', hasCiError: $hasCiError);

            // Relanzar la excepción para que Livewire muestre los errores
            throw $e;
        } catch (\Exception $e) {
            // Manejo de otros errores (base de datos, etc.)
            DB::rollBack();

            Log::error('Error en UserController@store', [
                'method' => __FUNCTION__,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $this->all()
            ]);

            $this->dispatch('error', message: $this->errorMessages['create']);
        }
    }

    public function update()
    {
        if (!$this->selected_id) {
            $this->dispatch('error', message: 'ID de usuario no proporcionado');
            return;
        }

        $this->validate([
            'name' => ['required', 'min:3', 'max:50'],
            'ci' => [
                'min:6',
                'max:20',
                'required',
                Rule::unique('user_profiles', 'ci')->ignore(
                    User::find($this->selected_id)->user_profile_id
                )
            ],
            'lastname' => ['required', 'min:3', 'max:50'],
            'phone' => ['required', 'min:6', 'max:50'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->selected_id)->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
            'barrio' => ['required', 'min:5', 'max:50'],
            'city' => ['required', 'min:5', 'max:50'],
            'address' => ['required', 'min:5', 'max:100'],
        ]);

        DB::beginTransaction();
        try {
            $record = User::find($this->selected_id);

            // Actualizar datos del perfil
            $record->profile->update([
                'name' => $this->name,
                'lastname' => $this->lastname,
                'phone' => $this->phone,
                'address' => $this->address,
                'barrio' => $this->barrio,
                'city' => $this->city
            ]);

            // Actualizar email si es diferente
            if ($record->email !== $this->email) {
                $record->update(['email' => $this->email]);
            }

            DB::commit();

            $this->updateTable();
            $this->dispatch('closeModal');
            $this->dispatch('registroExitoso', text: 'Edición exitosa...', bg: 'success');
            $this->dispatch('loadUsers');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en UserController', [
                'method' => __FUNCTION__,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $this->all()
            ]);
            $this->dispatch('error', message: $this->errorMessages['update']);
        }
    }

    #[On('deleteUsuario')]
    public function destroy(User $id)
    {
        DB::beginTransaction();
        try {
            $profile = $id->profile;
            $id->delete();

            // Eliminar el perfil si no tiene más usuarios asociados
            if ($profile->users()->count() === 0) {
                $profile->delete();
            }

            DB::commit();

            $this->updateTable();
            $this->dispatch('msg-deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en UserController', [
                'method' => __FUNCTION__,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $this->all()
            ]);
            $this->dispatch('error', message: $this->errorMessages['delete']);
        }
    }
}
