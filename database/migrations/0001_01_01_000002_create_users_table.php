<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Relación con tenant
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();

            // Relación con el perfil compartido
            $table->foreignId('user_profile_id')->constrained('user_profiles')->cascadeOnDelete();

            // Sucursal al que esta asignado el usuario
            $table->unsignedInteger('sucursal')->nullable(); // null=casa central ----- tenant.id=sucursal

            // Autenticación (única por tenant)
            $table->string('username', 20)->charset('ascii')->collation('ascii_bin');
            $table->string('email', 50)->charset('ascii')->collation('ascii_bin');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedTinyInteger('status')->default(1); // 1=activo, 2=inactivo, 3=suspendido 
            $table->rememberToken();

            $table->timestamps();

            // Índices importantes
            $table->unique(['tenant_id', 'email']);    // Email único por tenant
            $table->unique(['tenant_id', 'username']); // Username único por tenant
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
