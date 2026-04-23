<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('processed_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('period'); // e.g., "April 2026", "Week 16 2026"

            $table->enum('payment_type', [
                'salary',
                'wages',
                'bonus',
                'overtime',
                'commission',
                'deduction'
            ])->default('salary');

            $table->decimal('base_amount', 12, 2);
            $table->decimal('bonus_amount', 12, 2)->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->string('currency', 3)->default('KES');

            $table->text('notes')->nullable();
            $table->date('payment_date')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'paid',
                'cancelled'
            ])->default('pending');

            $table->timestamps();

            $table->index(['tenant_id', 'period']);
            $table->index(['user_id', 'period']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
