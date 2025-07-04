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
        Schema::create('configtenants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('tenants');

            $table->decimal('bajaLista1', 5, 2);
            $table->decimal('bajaLista2', 5, 2);
            $table->decimal('bajaLista3', 5, 2);

            $table->decimal('mediaLista1', 5, 2);
            $table->decimal('mediaLista2', 5, 2);
            $table->decimal('mediaLista3', 5, 2);

            $table->decimal('altaLista1', 5, 2);
            $table->decimal('altaLista2', 5, 2);
            $table->decimal('altaLista3', 5, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configtenants');
    }
};
