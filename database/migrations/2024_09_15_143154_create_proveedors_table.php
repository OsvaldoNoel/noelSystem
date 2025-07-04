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
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('tenants');
            $table->string('name', length: 50);
            $table->string('ruc', length: 20);
            $table->string('dv', length: 1)->nullable();
            $table->string('phone', length: 50)->nullable();
            $table->string('email', length: 50)->nullable();
            $table->string('adress', length: 100)->nullable();
            $table->string('barrio', length: 50)->nullable();
            $table->string('city', length: 50)->nullable(); 
            $table->string('status', length: 1)->default(1);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
