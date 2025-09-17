<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('motor_change_requests', function (Blueprint $table) {
            $table->foreignId('reviewed_by')
                ->nullable()
                ->after('status')
                ->constrained('users')
                ->nullOnDelete(); // if admin deleted, set null
        });
    }

    public function down(): void
    {
        Schema::table('motor_change_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by');
        });
    }
};