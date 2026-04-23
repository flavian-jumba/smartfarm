<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('task_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('field_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->date('log_date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();

            // GPS for check-in/out
            $table->decimal('check_in_latitude', 10, 8)->nullable();
            $table->decimal('check_in_longitude', 11, 8)->nullable();
            $table->decimal('check_out_latitude', 10, 8)->nullable();
            $table->decimal('check_out_longitude', 11, 8)->nullable();

            $table->text('activities_performed')->nullable();
            $table->text('notes')->nullable();
            $table->string('weather_conditions')->nullable();

            // Hours worked (calculated or manual)
            $table->decimal('hours_worked', 4, 2)->nullable();

            $table->enum('status', [
                'checked_in',
                'checked_out',
                'approved',
                'rejected'
            ])->default('checked_in');

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'log_date']);
            $table->index(['user_id', 'log_date']);
            $table->index('status');
            $table->unique(['user_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_logs');
    }
};
