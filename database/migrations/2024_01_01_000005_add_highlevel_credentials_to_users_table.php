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
            $table->text('highlevel_api_token')->nullable()->after('password');
            $table->string('highlevel_location_id')->nullable()->after('highlevel_api_token');
            $table->boolean('highlevel_connected')->default(false)->after('highlevel_location_id');
            $table->timestamp('highlevel_connected_at')->nullable()->after('highlevel_connected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'highlevel_api_token',
                'highlevel_location_id',
                'highlevel_connected',
                'highlevel_connected_at',
            ]);
        });
    }
};
