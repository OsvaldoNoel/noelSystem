<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names');

        // Tabla de permisos (globales para todos los tenants)
        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->id();
            $table->string('name');       // Ej: 'crear-usuarios'
            $table->string('guard_name'); // Normalmente 'web'
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        // Tabla de roles (específicos por tenant)
        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->id();
            $table->string('name');       // Ej: 'Propietario'
            $table->string('guard_name'); // Normalmente 'web'
            $table->unsignedBigInteger('tenant_id')->nullable()->comment('Null para landlord');
            $table->timestamps();

            $table->unique(['name', 'tenant_id']);
        });

        // Tabla pivot roles-permisos
        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        // Tabla pivot usuarios-roles (con tenant_id)
        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('tenant_id')->nullable()->comment('Para filtrar por tenant');
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(
                ['role_id', 'model_id', 'model_type', 'tenant_id'],
                'model_has_roles_role_model_tenant_primary'
            );

            // Índice para tenant_id para mejorar consultas
            $table->index(['tenant_id']);

            $table->index(['model_id', 'model_type']);
        });

        // Tabla pivot usuarios-permisos (con tenant_id)
        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('tenant_id')->nullable()->comment('Para filtrar por tenant');
            $table->timestamps();

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->index(['tenant_id', 'model_id', 'model_type'], 'model_has_permissions_tenant_model_index');
        });

        // Eliminar caché de permisos (modificado para evitar error)
        try {
            app('cache')->forget(config('permission.cache.key'));
        } catch (\Exception $e) {
            // Ignorar error si no hay sistema de caché configurado
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        Schema::dropIfExists($tableNames['role_has_permissions']);
        Schema::dropIfExists($tableNames['model_has_permissions']);
        Schema::dropIfExists($tableNames['model_has_roles']);
        Schema::dropIfExists($tableNames['roles']);
        Schema::dropIfExists($tableNames['permissions']);
    }
};
