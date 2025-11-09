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
        Schema::create('message_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('automation_campaigns')->onDelete('cascade');
            $table->foreignId('uploaded_file_id')->constrained('uploaded_files')->onDelete('cascade');

            $table->string('recipient_phone');
            $table->string('recipient_name')->nullable();

            // Message details
            $table->string('template_id');
            $table->text('message_content')->nullable();

            // API response
            $table->string('highlevel_message_id')->nullable();
            $table->json('api_response')->nullable();

            // Status: pending, sent, failed
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable();

            // Retry information
            $table->unsignedTinyInteger('retry_count')->default(0);
            $table->timestamp('last_retry_at')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('campaign_id');
            $table->index('status');
            $table->index(['campaign_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_logs');
    }
};
