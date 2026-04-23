<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('sender_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('receiver_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('messages')
                  ->cascadeOnDelete();

            $table->string('subject')->nullable();
            $table->text('body');

            $table->enum('type', [
                'text',
                'alert',
                'report',
                'request',
                'broadcast'
            ])->default('text');

            $table->enum('priority', [
                'low',
                'normal',
                'high',
                'urgent'
            ])->default('normal');

            $table->string('attachment_path')->nullable();
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['receiver_id', 'read_at']);
            $table->index(['sender_id', 'created_at']);
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
