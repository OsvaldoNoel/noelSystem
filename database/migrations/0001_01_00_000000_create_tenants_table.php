<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id(); // Mantenemos id() estándar por compatibilidad con relaciones

            $table->string('name', 20)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('domain', 50)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->unsignedInteger('sucursal')->nullable(); // null=casa central ----- tenant.id=nro de tenant al que pernetece
            $table->unsignedTinyInteger('subscription_type')->default(1); // 1=mensual, 2=anual, 3=demo
            $table->decimal('amount', 12, 2)->unsigned()->nullable(); // 10 enteros + 2 decimales
            $table->date('date_bill')->nullable();
            $table->unsignedTinyInteger('status')->default(1); // 1=activo, 2=inactivo, 3=suspendido

            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
