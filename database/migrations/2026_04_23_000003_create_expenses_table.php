<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('field_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('expense_category_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('recorded_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('KES');
            $table->date('expense_date');
            $table->string('receipt_path')->nullable();
            $table->string('vendor')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'expense_date']);
            $table->index(['field_id', 'expense_date']);
            $table->index(['expense_category_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
