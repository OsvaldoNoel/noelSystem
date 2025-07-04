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
        Schema::create('aportes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('aportante_id')->constrained('aportantes');

            $table->string('operacion', length: 1)->default(0);

            $table->integer('destino');
            $table->integer('monto'); 
            $table->integer('saldo');
            $table->string('date');
            $table->string('detail', length: 100);  
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aportes');
    }
};
