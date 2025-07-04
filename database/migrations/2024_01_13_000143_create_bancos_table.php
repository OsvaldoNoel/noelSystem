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
        Schema::create('bancos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('entidad_id')->constrained('entidads');
 
            $table->string('titular', length: 50);
            $table->string('cuenta', length: 20);
            $table->string('tipoCta', length: 20);
            $table->string('moneda', length: 20);
            $table->string('sobregiro', length: 20)->default(0);

            $table->string('oficial', length: 50)->nullable();
            $table->string('phone', length: 50)->nullable();

            $table->string('status', length: 2)->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancos');
    }
};
