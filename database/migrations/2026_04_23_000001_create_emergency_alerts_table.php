<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_alerts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('tenant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('title');
            $table->text('description');

            $table->enum('type', [
                'medical',
                'security',
                'equipment',
                'weather',
                'pest_outbreak',
                'other'
            ])->default('other');

            $table->enum('severity', [
                'low',
                'medium',
                'high',
                'critical'
            ])->default('high');

            $table->enum('status', [
                'pending',
                'acknowledged',
                'in_progress',
                'resolved',
                'dismissed'
            ])->default('pending');

            // GPS Location
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->string('image_path')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->foreignId('acknowledged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['user_id', 'created_at']);
            $table->index('severity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_alerts');
    }
};
