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
        // Rename table
        Schema::rename('automation_campaigns', 'contact_import_jobs');

        // Modify columns
        Schema::table('contact_import_jobs', function (Blueprint $table) {
            // Drop WhatsApp-specific columns
            $table->dropColumn(['template_id', 'template_name']);

            // Add tag-related columns
            $table->json('selected_tags')->after('selected_file_ids')->nullable();
            $table->json('new_tags')->after('selected_tags')->nullable();

            // Rename statistics columns for clarity
            $table->renameColumn('total_recipients', 'total_contacts');
            $table->renameColumn('total_sent', 'total_imported');

            // Update status enum to reflect new functionality
            $table->enum('status', ['draft', 'pending', 'processing', 'completed', 'failed', 'cancelled'])
                  ->default('draft')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_import_jobs', function (Blueprint $table) {
            // Restore WhatsApp columns
            $table->string('template_id')->nullable();
            $table->string('template_name')->nullable();

            // Drop tag columns
            $table->dropColumn(['selected_tags', 'new_tags']);

            // Restore original column names
            $table->renameColumn('total_contacts', 'total_recipients');
            $table->renameColumn('total_imported', 'total_sent');

            // Restore original status enum
            $table->enum('status', ['draft', 'scheduled', 'processing', 'completed', 'failed', 'cancelled'])
                  ->default('draft')
                  ->change();
        });

        Schema::rename('contact_import_jobs', 'automation_campaigns');
    }
};
