<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('field_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('assigned_to')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('assigned_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->enum('type', [
                'planting',
                'watering',
                'fertilizing',
                'pest_control',
                'harvesting',
                'maintenance',
                'inspection',
                'other'
            ])->default('other');

            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'urgent'
            ])->default('medium');

            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'cancelled',
                'overdue'
            ])->default('pending');

            $table->date('due_date');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // GPS verification for task completion
            $table->decimal('target_latitude', 10, 8)->nullable();
            $table->decimal('target_longitude', 11, 8)->nullable();
            $table->decimal('completion_latitude', 10, 8)->nullable();
            $table->decimal('completion_longitude', 11, 8)->nullable();
            $table->boolean('gps_verified')->default(false);
            $table->integer('gps_tolerance_meters')->default(100);

            $table->text('completion_notes')->nullable();
            $table->string('completion_image_path')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index(['field_id', 'status']);
            $table->index('due_date');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
