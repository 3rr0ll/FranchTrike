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
        Schema::table('login_logs', function (Blueprint $table) {
            // Change enum to include 'failed'
            $table->enum('status', ['success', 'fail', 'failed', 'locked'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('login_logs', function (Blueprint $table) {
            // Revert to original enum
            $table->enum('status', ['success', 'fail', 'locked'])->change();
        });
    }
};
