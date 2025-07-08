<?php

namespace App\Livewire\Tenant\Config\Users\List;

use App\Models\User;
use App\Models\userProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class UserListController extends Component
{
    public $selected_id, $username, $ci, $name, $lastname, $phone, $address, $barrio, $city, $email;
    public $componentName = 'Usuario';
    public $datos;

    public $errorMessages = [
        'create' => 'Error al crear usuario',
        'update' => 'Error al actualizar usuario',
        'delete' => 'Error al eliminar usuario'
    ];

    public function mount()
    {
        $this->updateTable();
    }

    public function updateTable()
    {
        $this->datos = User::query()
            ->with('profile') // Cargar la relación con el perfil
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

    #[On('statusUsuario')]
    public function status($id)
    {
        $record = User::findOrFail($id);
        ($record->status == 1) ? $record->update(['status' => 0]) : $record->update(['status' => 1]);
    }

    public function cancel()
    {
        $this->reset(['selected_id', 'username', 'ci', 'name', 'lastname', 'phone', 'email', 'address', 'barrio', 'city']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected $messages = [
        'ci.required' => 'El Nro. es requerido',
        'ci.integer' => 'Debe ser un numero entero',
        'ci.unique' => 'Este Nro. ya fue registrado',
        'ci.min' => 'Minimo 6 caracteres',
        'ci.max' => 'Como maximo 20 caracteres',

        'name.required' => 'El nombre es requerido',
        'name.min' => 'Minimo 3 caracteres',
        'name.max' => 'Caximo 50 caracteres',

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
        $this->validate([
            'name' => ['required', 'min:3', 'max:50'],
            'ci' => [
                'min:6',
                'max:20',
                'required',
                Rule::unique('user_profiles', 'ci') // Validar unicidad en user_profiles
            ],
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
        try {
            // Primero crear el perfil si no existe
            $profile = userProfile::firstOrCreate(
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

            // Luego crear el usuario asociado al tenant
            User::create([
                'tenant_id' => Auth::user()->tenant_id,
                'user_profile_id' => $profile->id,
                'username' => $this->ci . Auth::user()->tenant_id,
                'email' => $this->email,
                'password' => bcrypt($this->ci),
            ]);

            DB::commit();

            $this->dispatch('closeModal');
            $this->dispatch('registroExitoso', text: 'Registro exitoso...', bg: 'success');
            $this->updateTable();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en UserController', [
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

            // Actualizar datos del usuario (tabla users)
            // $record->update([
            //     'email' => $this->email,       //Se debe acutalizar a traves del administrador del tenant
            // ]);

            // Actualizar datos del perfil (tabla user_profiles)
            $record->profile->update([
                'name' => $this->name,
                'lastname' => $this->lastname,
                'phone' => $this->phone,
                'address' => $this->address,
                'barrio' => $this->barrio,
                'city' => $this->city
            ]);

            DB::commit();

            $this->updateTable();
            $this->dispatch('closeModal');
            $this->dispatch('registroExitoso', text: 'Edicion exitosa...', bg: 'success');
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
