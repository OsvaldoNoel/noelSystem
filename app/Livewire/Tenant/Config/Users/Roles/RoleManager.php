<?php

namespace App\Livewire\Tenant\Config\Users\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class RoleManager extends Component
{
    public $roles = [];
    public $permissions = [];
    public $selectedRole = null;
    public $rolePermissions = [];
    public $newRoleName = '';
    public $groupedPermissions = [];
    public $allSystemPermissions = [];
    public $editingRoleId = null;
    public $editedRoleName = '';

    public function mount()
    {
        $this->allSystemPermissions = Permission::all()->pluck('name')->toArray();
        $this->loadData();
    }

    #[On('loadData')]
    public function loadData()
    {
        $tenantId = Auth::user()->tenant_id; // Obtener tenant_id del usuario logueado

        $this->roles = Cache::remember("tenant:{$tenantId}:roles", now()->addDay(), function () use ($tenantId) {
            return Role::where(function ($query) use ($tenantId) {
                $query->whereNull('tenant_id')
                    ->orWhere('tenant_id', $tenantId);
            })
                ->where('name', '!=', 'Propietario')
                ->withCount('permissions')
                ->get()
                ->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'permissions_count' => $role->permissions_count,
                        'is_predefined' => is_null($role->tenant_id),
                        'tenant_id' => $role->tenant_id,
                    ];
                })
                ->sortByDesc('is_predefined')
                ->values()
                ->toArray();
        });
    }

    public function selectRole($roleId)
    {
        $this->selectedRole = Role::with('permissions')->find($roleId);
        $this->rolePermissions = $this->selectedRole->permissions->pluck('name')->toArray();

        // Definir qué permisos mostrar según el tipo de rol
        if (!is_null($this->selectedRole->tenant_id)) {
            // Roles personalizados: mostrar todos los permisos y permitir edición
            $this->permissions = $this->allSystemPermissions;
        } else {
            // Roles del sistema: mostrar todos los permisos del grupo pero deshabilitados
            $predefinedGroups = [
                'Admin' => $this->allSystemPermissions,
                'Ventas' => ['ver-ventas', 'crear-ventas', 'editar-ventas', 'eliminar-ventas'],
                'Compras' => ['ver-compras', 'crear-compras', 'editar-compras', 'eliminar-compras'],
                'Caja' => ['ver-caja', 'gestionar-caja'],
                'Reportes' => ['ver-reportes', 'generar-reportes']
            ];

            $this->permissions = $predefinedGroups[$this->selectedRole->name] ?? $this->selectedRole->permissions->pluck('name')->toArray();
        }

        $this->groupPermissions();
    }

    protected function groupPermissions()
    {
        $grouped = [];

        foreach ($this->permissions as $permission) {
            $parts = explode('-', $permission);
            $module = $parts[1] ?? 'otros';

            $moduleName = match ($module) {
                'usuarios' => 'Usuarios',
                'roles' => 'Roles',
                'ventas' => 'Ventas',
                'compras' => 'Compras',
                'caja' => 'Caja',
                'reportes' => 'Reportes',
                'configuracion' => 'Configuración',
                default => ucfirst($module)
            };

            $grouped[$moduleName][] = $permission;
        }

        // Ordenar los módulos según un orden específico
        $orderedModules = ['Usuarios', 'Roles', 'Ventas', 'Compras', 'Caja', 'Reportes', 'Configuración'];

        $orderedGrouped = [];
        foreach ($orderedModules as $module) {
            if (isset($grouped[$module])) {
                $orderedGrouped[$module] = $grouped[$module];
                unset($grouped[$module]);
            }
        }

        // Agregar los módulos restantes
        foreach ($grouped as $module => $permissions) {
            $orderedGrouped[$module] = $permissions;
        }

        $this->groupedPermissions = $orderedGrouped;
    }

    public function getChunkedPermissionsProperty()
    {
        if (empty($this->groupedPermissions)) {
            return [];
        }

        $indexed = [];
        foreach ($this->groupedPermissions as $module => $permissions) {
            $indexed[] = [$module => $permissions];
        }

        return array_chunk($indexed, 2);
    }

    protected function rules()
    {
        return [
            'newRoleName' => [
                'required',
                'string',
                'max:255',
                // La validación compleja la hacemos manualmente
            ],
        ];
    }

    protected function formatRoleName($name)
    {
        return ucwords(strtolower($name));
    }

    public function createRole()
    {
        $this->validate();

        // Capitalizar cada palabra antes de procesar
        $this->newRoleName = $this->formatRoleName($this->newRoleName);

        DB::beginTransaction();

        try {
            // 1. Primero verifica tu propia validación multi-tenant
            $exists = Role::where('name', $this->newRoleName)
                ->where('guard_name', 'web')
                ->where(function ($query) {
                    $query->whereNull('tenant_id')
                        ->orWhere('tenant_id', Auth::user()->tenant_id);
                })
                ->exists();

            if ($exists) {
                throw new \Exception(
                    $this->getExistingRoleMessage($this->newRoleName)
                );
            }

            // 2. Creación directa evitando el modelo Spatie
            $roleId = DB::table('roles')->insertGetId([
                'name' => $this->newRoleName,
                'guard_name' => 'web',
                'tenant_id' => Auth::user()->tenant_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Limpiar cache de Spatie
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            DB::commit();

            $this->reset('newRoleName');
            $this->loadData();
            $this->dispatch('notify', text: 'Rol creado exitosamente', bg: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: $e->getMessage(), bg: 'danger');
            return;
        }

        $this->dispatch('loadRoles');
    }

    protected function getExistingRoleMessage($roleName)
    {
        $role = Role::where('name', $roleName)
            ->where('guard_name', 'web')
            ->first();

        return $role
            ? ($role->tenant_id ?
                "El rol {$roleName} ya existe en este tenant" :
                "El rol {$roleName} ya existe en el sistema")
            : "Error de validación de rol";
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'newRoleName') {
            $this->validateRoleName();
        }
        $this->dispatch('loadRoles');
    }

    protected function validateRoleName()
    {
        $this->resetValidation('newRoleName');

        $exists = Role::where('name', $this->newRoleName)
            ->where('guard_name', 'web')
            ->where(function ($query) {
                $query->whereNull('tenant_id')
                    ->orWhere('tenant_id', Auth::user()->tenant_id);
            })
            ->exists();

        if ($exists) {
            $this->addError('newRoleName', $this->getExistingRoleMessage($this->newRoleName));
        }
    }

    public function updateRolePermissions()
    {
        if (!$this->selectedRole) return;

        // Prevenir cambios en roles del sistema
        if (is_null($this->selectedRole->tenant_id)) {
            $this->dispatch('notify', text: 'No puedes modificar los permisos de roles del sistema', bg: 'danger');
            return;
        }

        $this->selectedRole->syncPermissions($this->rolePermissions);
        $this->loadData(); // Actualizar conteo de permisos
        $this->dispatch('loadRoles');
    }

    // Método para iniciar la edición
    public function startEditing($roleId)
    {
        $this->editingRoleId = $roleId;
        $role = Role::find($roleId);
        $this->editedRoleName = $this->formatRoleName($role->name);
    }

    // Método para cancelar la edición
    public function cancelEditing()
    {
        $this->editingRoleId = null;
        $this->editedRoleName = '';
    }

    // Método para actualizar el nombre del rol (refactorizado)
    public function updateRoleName()
    {
        // Capitalizar cada palabra antes de procesar
        $this->editedRoleName = $this->formatRoleName($this->editedRoleName);

        DB::beginTransaction();

        try {
            // 1. Validar el nombre del rol
            $this->validateEditedRoleName();

            // 2. Actualizar el rol en la base de datos
            DB::table('roles')
                ->where('id', $this->editingRoleId)
                ->update([
                    'name' => $this->editedRoleName,
                    'updated_at' => now()
                ]);

            // 3. Limpiar cache de Spatie
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            DB::commit();

            // 4. Notificar éxito y actualizar UI
            $this->dispatch('notify', text: 'Nombre de rol actualizado', bg: 'success');
            $this->loadData();
            $this->cancelEditing();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: $e->getMessage(), bg: 'danger');
        }
    }

    // Método protegido para validar el nombre del rol editado
    protected function validateEditedRoleName()
    {
        // Asegurar que estamos validando el nombre capitalizado
        $roleNameToValidate = $this->formatRoleName($this->editedRoleName);

        $rules = [
            'editedRoleName' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($roleNameToValidate) {
                    $exists = Role::where('name', $roleNameToValidate)
                        ->where('guard_name', 'web')
                        ->where('id', '!=', $this->editingRoleId)
                        ->where(function ($query) {
                            $query->whereNull('tenant_id')
                                ->orWhere('tenant_id', Auth::user()->tenant_id);
                        })
                        ->exists();

                    if ($exists) {
                        $fail($this->getExistingRoleMessage($roleNameToValidate));
                    }
                }
            ],
        ];

        $validator = \Illuminate\Support\Facades\Validator::make(
            ['editedRoleName' => $roleNameToValidate],
            $rules
        );

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }

    // Agregar este método para validación en tiempo real
    public function updatedEditedRoleName()
    {
        try {
            $this->validateEditedRoleName();
            $this->resetValidation('editedRoleName');
        } catch (\Exception $e) {
            $this->addError('editedRoleName', $e->getMessage());
        }
    }

    public function deleteRole($roleId)
    {
        // Si estamos editando este rol, cancelar la edición primero
        if ($this->editingRoleId == $roleId) {
            $this->cancelEditing();
        }

        $this->selectedRole = Role::with('permissions')->find($roleId);

        if (!$this->selectedRole) return;

        // No permitir eliminar roles del sistema
        if (is_null($this->selectedRole->tenant_id)) {
            $this->dispatch('notify', text: 'No puedes eliminar roles del sistema', bg: 'danger');
            return;
        }

        // Verificar si el rol está asignado a algún usuario
        if ($this->selectedRole->users()->count() > 0) {
            $this->dispatch('notify', text: 'No puedes eliminar este rol porque está asignado a algún usuario', bg: 'danger');
            return;
        }

        $this->selectedRole->delete();
        $this->selectedRole = null;
        $this->loadData();
        $this->dispatch('notify', text: 'Rol eliminado', bg: 'success');
        $this->dispatch('loadRoles');
    }

    public function render()
    {
        return view('livewire.tenant.config.users.roles.role-manager');
    }
}
