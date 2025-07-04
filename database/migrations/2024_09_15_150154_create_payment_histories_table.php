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
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->char('currency_code', 3)->default('PYG');
            $table->date('payment_date');
            $table->unsignedTinyInteger('payment_method'); // 1=transferencia, 2=tarjeta, etc.
            $table->string('transaction_id')->nullable();  // Ejemplo de Stripe
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('payment_date');
        });
    }

     
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
