<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');    //Usuario que registro la compra
            $table->foreignId('proveedor_id')->constrained('proveedors')->onDelete('restrict');

            // Campos de configuración
            $table->date('date');  // Solo fecha (ej: 2023-12-31)
            $table->string('numero_factura', 15)->nullable();
            $table->string('otrosRecibo', 15)->nullable();
            $table->unsignedInteger('timbrado')->nullable();  // Para 8-10 dígitos

            // Campos de estado (0/1)
            $table->unsignedTinyInteger('condCompr')->default(1); // 1=contado, 2=crédito
            $table->unsignedTinyInteger('tipoCompr')->default(1); // 1=legal, 2=común, 3=otros
            $table->unsignedTinyInteger('status')->default(0); // 0=pagado, 1=pendiente
            $table->unsignedTinyInteger('stock')->default(0);  // 0=stock, 1=gasto, 2=otros
            $table->unsignedTinyInteger('items');                // Cantidad de ítems (0-255)

            // Campos monetarios
            $table->decimal('total', 12, 2);
            $table->decimal('gravada_10', 12, 2);
            $table->decimal('gravada_5', 12, 2);
            $table->decimal('exenta', 12, 2);
            $table->decimal('iva_10', 12, 2);
            $table->decimal('iva_5', 12, 2);

            $table->timestamps();
            $table->softDeletes(); // 👈 Aquí se agrega el borrado lógico

            // Índices adicionales
            $table->index('date');
            $table->index('numero_factura');
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
