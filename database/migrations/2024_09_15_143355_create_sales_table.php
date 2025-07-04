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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->decimal('total', 10, 2);
            $table->integer('items');
            $table->integer('descuento');
            $table->integer('gravada');
            $table->integer('iva');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
