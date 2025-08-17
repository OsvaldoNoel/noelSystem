<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class S02_RolesAndPermissionsSeeder extends Seeder
{
    // Módulos del sistema
    private const MODULE_USERS = 'usuarios';
    private const MODULE_ROLES = 'roles';
    private const MODULE_SALES = 'ventas';
    private const MODULE_PURCHASES = 'compras';
    private const MODULE_CASH = 'caja';
    private const MODULE_REPORTS = 'reportes';
    private const MODULE_CONFIG = 'configuracion';

    /**
     * Ejecuta el seeder
     *
     * @return void
     */
    public function run()
    {
        $this->createPermissions();
        $this->createRoles();
    }

    /**
     * Crea los permisos del sistema
     *
     * @return void
     */
    private function createPermissions(): void
    {
        $modules = [
            self::MODULE_USERS => ['ver', 'crear', 'editar', 'eliminar'],
            self::MODULE_ROLES => ['ver', 'crear', 'editar', 'eliminar'],
            self::MODULE_SALES => ['ver', 'crear', 'editar', 'eliminar'],
            self::MODULE_PURCHASES => ['ver', 'crear', 'editar', 'eliminar'],
            self::MODULE_CASH => ['ver', 'gestionar'],
            self::MODULE_REPORTS => ['ver', 'generar'],
            self::MODULE_CONFIG => ['ver', 'gestionar']
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}-{$module}",
                    'guard_name' => 'web'
                ]);
            }
        }
    }

    /**
     * Crea los roles del sistema con sus permisos
     *
     * @return void
     */
    private function createRoles(): void
    {
        $roles = [
            User::ROLE_OWNER => Permission::all()->pluck('name')->toArray(),
            User::ROLE_ADMIN => Permission::all()->pluck('name')->toArray(),
            User::ROLE_SALES => $this->getModulePermissions(self::MODULE_SALES),
            User::ROLE_PURCHASES => $this->getModulePermissions(self::MODULE_PURCHASES),
            User::ROLE_CASHIER => $this->getModulePermissions(self::MODULE_CASH),
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
                'tenant_id' => null // Roles globales
            ]);
            
            $role->syncPermissions($permissions);
        }
    }

    /**
     * Obtiene los permisos de un módulo específico
     *
     * @param string $module
     * @return array
     */
    private function getModulePermissions(string $module): array
    {
        return Permission::where('name', 'like', "%-{$module}")
            ->pluck('name')
            ->toArray();
    }
}