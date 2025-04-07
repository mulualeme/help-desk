<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->text('message');
            $table->enum('message_type', ['reply', 'note'])->default('reply');
            $table->boolean('is_internal')->default(false); // True for staff-only visibility
            $table->unsignedBigInteger('user_id')->nullable(); // Can be null for system messages
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->boolean('is_system_message')->default(false);
            $table->boolean('has_attachments')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};
