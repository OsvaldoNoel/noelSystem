<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            // Datos personales (únicos por persona)
            $table->string('ci', 20)->unique()->charset('ascii')->collation('ascii_bin');
            $table->string('name', 50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('lastname', 50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');

            // Contacto
            $table->string('phone', 50)->charset('ascii');

            // Ubicación
            $table->string('address', 100)->nullable()->charset('utf8mb4');
            $table->string('barrio', 50)->nullable()->charset('utf8mb4');
            $table->string('city', 50)->nullable()->charset('utf8mb4');

            $table->timestamps();

            // Índices importantes
            $table->index('ci');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
