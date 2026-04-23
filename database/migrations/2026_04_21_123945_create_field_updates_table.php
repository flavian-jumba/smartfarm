<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('field_updates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('field_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('agent_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->enum('stage', [
                'planted',
                'growing',
                'ready',
                'harvested'
            ]);

            $table->text('notes')->nullable();

            $table->timestamps();

            // Performance
            $table->index(['field_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_updates');
    }
};