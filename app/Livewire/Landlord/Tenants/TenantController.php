<?php

namespace App\Livewire\Landlord\Tenants;

use App\Models\Tenant;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\On;
use Livewire\Component;

class TenantController extends Component
{
    // Propiedades para Tenant
    public $selected_id, $name, $sucursal, $status;
    public $componentName = 'Tenant';
    public $search = '';
    public $datos;
    public $is_branch = false; // Para controlar si es sucursal o no
    public $tenantsList = []; // Lista de Tenants para el select 
    public $tenant_type;
    public $profileFound = false;
    public $ownerFound = false;

    // Propiedades para el Usuario Propietario
    public $ci, $name_user, $lastname, $phone, $email, $barrio, $city, $address;

    public function mount()
    {
        $this->updateTable();
    }

    public function updatedSearch()
    {
        $this->updateTable();
    }

    #[On('updateTable')]
    public function updateTable()
    {
        // Primero obtenemos los mainTenants que coinciden con la búsqueda
        $mainTenants = Tenant::query()
            ->whereNull('sucursal')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'sucursal', 'status', 'tenant_type']); 

        // Luego obtenemos TODAS sus sucursales (sin filtro de búsqueda)
        $branches = Tenant::query()
            ->whereNotNull('sucursal')
            ->whereIn('sucursal', $mainTenants->pluck('id'))
            ->orderBy('name')
            ->get(['id', 'name', 'sucursal', 'status', 'tenant_type']); 

        // Construimos la estructura ordenada
        $orderedData = [];
        foreach ($mainTenants as $position => $main) {
            $orderedData[] = [
                ...$main->toArray(),
                'sort_order' => ($position + 1) * 1000,
                'is_main' => true
            ];

            // Agregamos todas sus sucursales
            $branches->where('sucursal', $main->id)
                ->each(function ($branch, $index) use (&$orderedData, $position) {
                    $orderedData[] = [
                        ...$branch->toArray(),
                        'sort_order' => (($position + 1) * 1000) + ($index + 1),
                        'is_main' => false
                    ];
                });
        }

        $this->datos = $orderedData;
        $this->dispatch('tableUpdated', datos: $this->datos);
    }

    public function cancel()
    {
        $this->reset(
            'selected_id',
            'name',
            'sucursal',
            'is_branch',
            'tenant_type',
            'tenantsList',
            'ci',
            'name_user',
            'lastname',
            'phone',
            'email',
            'barrio',
            'city',
            'address',
        );
        $this->resetErrors();
    }

    public function resetErrors()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // En TenantController.php

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
            'ci.max' => 'Debe tener maximo 15 dígitos',
        ]);

        // Buscar el perfil en la base de datos
        $profile = UserProfile::where('ci', $this->ci)->first();
        $this->profileFound = (bool)$profile;

        $this->resetErrors();

        if ($profile) {
            // Si encontramos el perfil, llenamos los campos
            $this->name_user = $profile->name;
            $this->lastname = $profile->lastname;
            $this->phone = $profile->phone;
            $this->address = $profile->address;
            $this->barrio = $profile->barrio;
            $this->city = $profile->city;
            $this->email = null;

            $this->dispatch('profileFound');
        } else {

            $this->name_user = null;
            $this->lastname = null;
            $this->email = null;
            $this->phone = null;
            $this->address = null;
            $this->barrio = null;
            $this->city = null;

            $this->dispatch('profileNotFound');
        }
    }

    #[On('searchOwner')]
    public function searchOwner($tenantId)
    {
        // Buscar el primer usuario propietario de la empresa principal
        $owner = User::where('tenant_id', $tenantId)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Propietario');
            })
            ->oldest()
            ->with('profile')
            ->first();

        $this->ownerFound = (bool)$owner;

        if ($owner && $owner->profile) {
            // Si encontramos el propietario, llenamos los campos
            $this->ci = $owner->profile->ci;
            $this->name_user = $owner->profile->name;
            $this->lastname = $owner->profile->lastname;
            $this->phone = $owner->profile->phone;
            $this->address = $owner->profile->address;
            $this->barrio = $owner->profile->barrio;
            $this->city = $owner->profile->city;
            $this->email = $owner->email;

            $this->dispatch('ownerFound');
        }

        //$this->skipRender();
    }

    public function store()
    {
        // Validación para Tenant
        $this->validate([
            'name' => [
                'required',
                'min:3',
                'max:50',
                // Modificamos la regla unique para sucursales
                $this->is_branch
                    ? Rule::unique('tenants')->where(function ($query) {
                        return $query->where('sucursal', $this->sucursal);
                    })
                    : Rule::unique('tenants')->whereNull('sucursal')
            ],
            'sucursal' => [
                Rule::requiredIf($this->is_branch),
                'nullable',
                'exists:tenants,id'
            ],
            'tenant_type' => [
                'nullable',
                Rule::requiredIf(!$this->is_branch),
                Rule::in([1, 2, 3, 4]) // 1=POS, 2=Servicios, 3=MicroVentas, 4=Restaurante
            ],
            // Validación para Usuario Propietario
            'ci' => [
                'required',
                'numeric',
                'min:100000',
                'max:999999999999999',
            ],
            'name_user' => ['required', 'min:3', 'max:50'],
            'lastname' => ['required', 'min:3', 'max:50'],
            'phone' => ['required', 'min:6', 'max:50'],
            'email' => [
                'required',
                'email',
            ],
            'barrio' => ['required', 'min:5', 'max:50'],
            'city' => ['required', 'min:5', 'max:50'],
            'address' => ['required', 'min:5', 'max:100'],
        ], [
            // Mensajes de error para Tenant
            'name.required' => $this->is_branch
                ? 'El nombre de la sucursal es requerida'
                : 'El nombre de la empresa es requerido',
            'name.unique' => $this->is_branch
                ? 'Ya existe una sucursal con este nombre para esta empresa'
                : 'Ya existe una empresa con este nombre',
            'name.min' => 'Mínimo 3 caracteres',
            'name.max' => 'Máximo 50 caracteres',
            'sucursal.required' => 'Debe seleccionar una Empresa',
            'sucursal.exists' => 'Empresa no válida',
            'tenant_type.required' => 'Debe seleccionar un tipo de empresa',
            'tenant_type.in' => 'Tipo de empresa no válido',
            // Mensajes de error para Usuario
            'ci.required' => 'La cédula es requerida',
            'ci.numeric' => 'La cédula debe ser numérico',
            'ci.min' => 'Debe tener al menos 6 dígitos',
            'ci.max' => 'Debe tener maximo 15 dígitos',
            'ci.unique' => 'Esta cédula ya está registrada',
            'name_user.required' => 'El nombre es requerido',
            'lastname.required' => 'El apellido es requerido',
            'phone.required' => 'El teléfono es requerido',
            'email.required' => 'El email es requerido',
            'email.unique' => 'Este email ya está registrado',
            'barrio.required' => 'El barrio es requerido',
            'city.required' => 'La ciudad es requerida',
            'address.required' => 'La dirección es requerida',
        ]);

        DB::beginTransaction();
        try {
            // Verificar que existan los permisos básicos
            $requiredPermissions = ['ver-ventas', 'ver-compras', 'ver-caja'];
            foreach ($requiredPermissions as $perm) {
                if (!Permission::where('name', $perm)->exists()) {
                    throw new \Exception("El permiso requerido '$perm' no existe en el sistema");
                }
            }

            // Obtener el tenant_type para sucursales
            $tenantType = $this->is_branch
                ? Tenant::find($this->sucursal)->tenant_type
                : $this->tenant_type;

            // 1. Crear el Tenant
            $tenant = Tenant::create([
                'name' => $this->name,
                'sucursal' => $this->is_branch ? $this->sucursal : null,
                'status' => 1,
                'tenant_type' => $tenantType
            ]);

            if (!$this->is_branch) {
                // Primero crear el perfil si no existe
                $profile = userProfile::firstOrCreate(
                    ['ci' => $this->ci],
                    [
                        'name' => $this->name_user,
                        'lastname' => $this->lastname,
                        'phone' => $this->phone,
                        'address' => $this->address,
                        'barrio' => $this->barrio,
                        'city' => $this->city
                    ]
                );

                // 3. Crear el usuario propietario
                $user = User::create([
                    'tenant_id' => $tenant->id,
                    'user_profile_id' => $profile->id,
                    'email' => $this->email,
                    'username' => $this->ci . $tenant->id, // CI + Tenant ID
                    'password' => bcrypt('12345678'), // Contraseña por defecto, se debe cambiar luego
                ]);


                // 4. Asignar rol de Propietario
                $propietarioRole = Role::where('name', 'Propietario')
                    ->whereNull('tenant_id') // Solo el rol global
                    ->first();
                if (!$propietarioRole) {
                    throw new \Exception("El rol 'Propietario' no existe en el sistema");
                }
                $user->assignRole($propietarioRole, $tenant->id);
            }

            DB::commit();

            $this->dispatch('closeModal');
            if ($this->is_branch) {
                $this->dispatch('registroExitoso', text: 'Sucursal creada exitosamente', bg: 'success');
            } else {
                $this->dispatch('registroExitoso', text: 'Empresa y propietario creados exitosamente', bg: 'success');
            };
            $this->cancel();
            $this->updateTable();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch(
                'toastError',
                title: 'Error al crear el tenant',
                msg: $e->getMessage()
            );
        }
    }

    public function update()
    {
        $tenant = Tenant::findOrFail($this->selected_id);

        // Validación manual para update que incluye ignore
        $this->validate([
            'name' => [
                'required',
                'min:3',
                'max:50',
                Rule::unique('tenants')->where(function ($query) use ($tenant) {
                    // Para sucursales, verificar unicidad dentro del mismo tenant principal
                    if ($tenant->isBranch()) {
                        return $query->where('sucursal', $tenant->sucursal);
                    }
                    // Para tenants principales, verificar unicidad entre tenants principales
                    return $query->whereNull('sucursal');
                })->ignore($tenant->id)
            ]
        ], [
            'name.required' => 'El nombre no puede quedar vacío',
            'name.min' => 'El nombre debe tener al menos tres caracteres',
            'name.max' => 'El nombre debe tener como máximo 50 caracteres',
            'name.unique' => $tenant->isBranch()
                ? 'Ya existe una sucursal con este nombre para esta empresa'
                : 'Ya existe una empresa principal con este nombre'
        ]);

        $tenant->update([
            'name' => $this->name
        ]);

        $this->cancel();
        $this->dispatch('closeModal');
        $this->dispatch('registroExitoso', text: 'Editado exitosamente...', bg: 'success');
        $this->updateTable();
    }

    #[On('statusTenant')]
    public function status($id)
    {
        $record = Tenant::findOrFail($id);
        $record->update(['status' => !$record->status]);
    }

    // #[On('deleteTenant')]
    // public function destroy(Tenant $id)
    // {
    //     $id->delete();
    //     $this->updateTable();
    //     $this->dispatch('msg-deleted');
    // }
}
