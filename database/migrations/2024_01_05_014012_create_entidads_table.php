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
        Schema::create('entidads', function (Blueprint $table) {
            $table->id();

            $table->string('entidad', length: 50);
            $table->string('name', length: 50);
            $table->string('nombre', length: 50);

            $table->string('color', length: 20);
            $table->string('addres', length: 50);
            $table->string('phone', length: 20);

            $table->string('image',100)->nullable();
            $table->string('status', length: 2)->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidads');
    }
};
