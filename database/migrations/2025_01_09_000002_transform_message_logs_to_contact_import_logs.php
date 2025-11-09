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
        Schema::rename('message_logs', 'contact_import_logs');

        // Modify columns
        Schema::table('contact_import_logs', function (Blueprint $table) {
            // Rename foreign key column
            $table->renameColumn('campaign_id', 'import_job_id');

            // Drop WhatsApp-specific columns
            $table->dropColumn(['template_id', 'message_content', 'highlevel_message_id']);

            // Rename recipient columns to contact columns
            $table->renameColumn('recipient_phone', 'contact_phone');
            $table->renameColumn('recipient_name', 'contact_name');

            // Add contact-specific columns
            $table->string('highlevel_contact_id')->after('contact_name')->nullable();
            $table->json('contact_data')->after('highlevel_contact_id')->nullable();
            $table->json('assigned_tags')->after('contact_data')->nullable();

            // Rename sent_at to imported_at
            $table->renameColumn('sent_at', 'imported_at');

            // Keep status, retry_count, last_retry_at, api_response, error_message as they are useful
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_import_logs', function (Blueprint $table) {
            // Restore foreign key column name
            $table->renameColumn('import_job_id', 'campaign_id');

            // Restore WhatsApp columns
            $table->string('template_id')->nullable();
            $table->text('message_content')->nullable();
            $table->string('highlevel_message_id')->nullable();

            // Restore recipient column names
            $table->renameColumn('contact_phone', 'recipient_phone');
            $table->renameColumn('contact_name', 'recipient_name');

            // Drop contact-specific columns
            $table->dropColumn(['highlevel_contact_id', 'contact_data', 'assigned_tags']);

            // Restore sent_at
            $table->renameColumn('imported_at', 'sent_at');
        });

        Schema::rename('contact_import_logs', 'message_logs');
    }
};
