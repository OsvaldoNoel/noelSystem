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
            
            // Rol como tinyint (1=tenant, 2=landlord, 3=admin, etc.)
            $table->unsignedTinyInteger('role')->default(1);
            
            // Datos personales
            $table->string('ci', 20)->charset('ascii')->collation('ascii_bin');
            $table->string('username', 15)->charset('ascii')->collation('ascii_bin');
            $table->string('name', 50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('lastname', 50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            
            // Contacto
            $table->string('phone', 20)->charset('ascii');
            $table->string('email', 50)->charset('ascii')->collation('ascii_bin');
            
            // Ubicación
            $table->string('address', 100)->nullable()->charset('utf8mb4');
            $table->string('barrio', 50)->nullable()->charset('utf8mb4');
            $table->string('city', 50)->nullable()->charset('utf8mb4');
            
            // Autenticación
            $table->unsignedTinyInteger('status')->default(1); // 1=activo, 2=inactivo, 3=suspendido
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            
            $table->timestamps();

            // Índices importantes
            $table->index('ci');
            $table->index('username');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};