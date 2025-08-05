<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class S02_RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Permisos globales (no específicos de tenant)
        $permissions = [
            // Usuarios
            'ver-usuarios', 'crear-usuarios', 'editar-usuarios', 'eliminar-usuarios',
            // Roles
            'ver-roles', 'crear-roles', 'editar-roles', 'eliminar-roles',
            // Ventas
            'ver-ventas', 'crear-ventas', 'editar-ventas', 'eliminar-ventas',
            // Compras
            'ver-compras', 'crear-compras', 'editar-compras', 'eliminar-compras',
            // Caja
            'ver-caja', 'gestionar-caja',
            // Reportes
            'ver-reportes', 'generar-reportes',
            // Configuración
            'ver-configuracion', 'gestionar-configuracion'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Crear roles globales si no existen
        $roles = [
            'Propietario' => Permission::all()->pluck('name')->toArray(),
            'Admin' => Permission::all()->pluck('name')->toArray(),
            'Ventas' => ['ver-ventas', 'crear-ventas', 'editar-ventas'],
            'Compras' => ['ver-compras', 'crear-compras', 'editar-compras'],
            'Caja' => ['ver-caja', 'gestionar-caja'],
            'Reportes' => ['ver-reportes', 'generar-reportes']
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
                'tenant_id' => null
            ]);
            $role->syncPermissions($rolePermissions);
        }
 
    }
}