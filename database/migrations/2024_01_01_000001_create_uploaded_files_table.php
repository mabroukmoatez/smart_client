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
        Schema::create('uploaded_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Original file information
            $table->string('original_filename');
            $table->string('original_file_path');
            $table->string('original_mime_type');
            $table->unsignedBigInteger('original_file_size'); // in bytes

            // Converted CSV information
            $table->string('converted_csv_path');
            $table->unsignedInteger('row_count')->default(0);

            // Column mapping (stored as JSON)
            $table->json('column_mapping'); // {phone_column: 'Phone', name_column: 'Name'}

            // Metadata
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploaded_files');
    }
};
