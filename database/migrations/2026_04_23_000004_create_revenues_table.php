<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('field_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('recorded_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->enum('source', [
                'harvest_sale',
                'livestock_sale',
                'equipment_rental',
                'subsidy',
                'other'
            ])->default('harvest_sale');

            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('KES');
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('unit')->nullable(); // kg, bags, pieces, etc.
            $table->decimal('unit_price', 10, 2)->nullable();

            $table->date('revenue_date');
            $table->string('buyer_name')->nullable();
            $table->string('buyer_contact')->nullable();
            $table->string('receipt_path')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'revenue_date']);
            $table->index(['field_id', 'revenue_date']);
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revenues');
    }
};
