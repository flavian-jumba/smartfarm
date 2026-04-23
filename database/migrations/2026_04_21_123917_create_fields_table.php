<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('crop_type');

            $table->date('planting_date');

            $table->enum('current_stage', [
                'planted',
                'growing',
                'ready',
                'harvested'
            ])->default('planted');

            // Relationships
            $table->foreignId('agent_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('tenant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->timestamps();

            // Indexes for performance
            $table->index(['tenant_id', 'agent_id']);
            $table->index('current_stage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};