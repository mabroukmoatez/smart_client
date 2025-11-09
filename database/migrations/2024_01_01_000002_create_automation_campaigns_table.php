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
        Schema::create('automation_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->text('description')->nullable();

            // Template information
            $table->string('template_id');
            $table->string('template_name');

            // Selected files (JSON array of file IDs)
            $table->json('selected_file_ids');

            // Scheduling
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Statistics
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('total_sent')->default(0);
            $table->unsignedInteger('total_failed')->default(0);
            $table->unsignedInteger('total_pending')->default(0);

            // Status: draft, scheduled, processing, completed, failed
            $table->enum('status', ['draft', 'scheduled', 'processing', 'completed', 'failed', 'cancelled'])
                  ->default('draft');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automation_campaigns');
    }
};
