<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compra_items', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('compra_id')->constrained('compras')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict'); 

            $table->unsignedInteger('quantity'); // Para 8-10 dígitos

            // Campos monetarios
            $table->decimal('price_unit', 10, 2); // 10 dígitos totales, 2 decimales
            $table->decimal('price_unit_discounted', 10, 2); // precio con descuento
            $table->decimal('discount_amount', 10, 2); // descuento en valor absoluto
            $table->decimal('discount_percent', 5, 2); // descuento porcentual (100.00% máximo)
            $table->decimal('row_total', 10, 2)->unsigned(); // total de la fila - Solo valores positivos

            $table->unsignedTinyInteger('iva')->default(0);  // 0=10%, 1=5%, 2=Excenta

            $table->timestamps();

            // Índices
            $table->index(['compra_id', 'product_id']);
            $table->index('tenant_id');
        });
 
    }


    public function down(): void
    {
        Schema::dropIfExists('compra_items');
    }
};
