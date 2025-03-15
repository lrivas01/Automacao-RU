<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Relacionado ao estudante
            $table->integer('tokens')->unsigned()->check('tokens >= 1'); // Quantidade de fichas compradas (mínimo 1)
            $table->decimal('amount', 10, 2)->check('amount >= 0'); // Valor pago
            $table->enum('payment_method', ['pix', 'card']); // Método de pagamento
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status do pagamento
            $table->timestamps(); // created_at e updated_at
            $table->softDeletes(); // deleted_at (opcional)
            
            // Índices para melhorar o desempenho
            $table->index('status');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('payments');
    }
};