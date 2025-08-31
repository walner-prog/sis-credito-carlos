<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Lista de entidades clave
        $entities = [
            'user',
            'role',
            'permision',
            'abono',
            'cartera',
            'cliente',
            'credito',
        ];

        // Acciones por entidad
        $actions = ['_ver', '_crear', '_editar', '_eliminar'];

        // Roles
        $adminRole   = Role::where('name', 'Administrador')->first();
        $cobradorRole = Role::where('name', 'Cobrador')->first();

        // Crear todos los permisos y asignarlos
        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                $permissionName = $entity . $action;

                // Crear si no existe
                $permission = Permission::firstOrCreate([
                    'name'       => $permissionName,
                    'guard_name' => 'web',
                ]);

                // Admin siempre tiene todos
                if ($adminRole) {
                    $adminRole->givePermissionTo($permission);
                }
            }
        }

        // 🔹 PERMISOS DEL COBRADOR
        if ($cobradorRole) {
            // Ejemplo: el cobrador solo puede ver clientes y crear/editar abonos
            $cobradorPermissions = [
                'cliente_ver',
                'cliente_crear',
                'cliente_editar',
                'cliente_eliminar',
                'credito_ver',
                'credito_crear',
                'credito_editar',
                'credito_eliminar',
                'abono_ver',
                'abono_crear',
                'abono_editar',
                'abono_eliminar',

            ];

            foreach ($cobradorPermissions as $perm) {
                $permission = Permission::where('name', $perm)->first();
                if ($permission) {
                    $cobradorRole->givePermissionTo($permission);
                }
            }
        }
    }
}
