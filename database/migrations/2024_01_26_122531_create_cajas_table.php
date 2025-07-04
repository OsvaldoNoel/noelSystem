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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('tenants'); 

            $table->string('name', length: 50);
            $table->string('ubi', length: 50); 

            $table->string('status', length: 2)->default(1);
            $table->string('estado', length: 1)->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
