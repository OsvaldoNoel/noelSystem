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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('subcategoria_id')->constrained('subcategorias');
            $table->foreignId('marca_id')->constrained('marcas');
            $table->foreignId('presentation_id')->constrained('presentations'); 

            $table->string('code',25);
            $table->string('name',50);

            $table->integer('stock');
            $table->integer('stockMin');
            $table->integer('stockMax');

            $table->integer('costoPromedio');

            $table->integer('priceList1');
            $table->integer('priceList2');
            $table->integer('priceList3');

            $table->integer('tipoUtilidad')->default(2);
            $table->integer('redondeo')->default(100);
            $table->boolean('vto')->default(false); 

            $table->string('image',100)->nullable();
            $table->boolean('status')->default(true); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
