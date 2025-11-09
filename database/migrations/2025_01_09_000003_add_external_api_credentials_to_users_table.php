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
        Schema::table('users', function (Blueprint $table) {
            $table->string('external_api_url')->nullable()->after('highlevel_connected_at');
            $table->text('external_api_token')->nullable()->after('external_api_url');
            $table->boolean('external_api_connected')->default(false)->after('external_api_token');
            $table->timestamp('external_api_connected_at')->nullable()->after('external_api_connected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'external_api_url',
                'external_api_token',
                'external_api_connected',
                'external_api_connected_at',
            ]);
        });
    }
};
