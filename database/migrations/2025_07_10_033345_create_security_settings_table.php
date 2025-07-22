<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('security_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default security settings
        DB::table('security_settings')->insert([
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'description' => 'Maximum number of failed login attempts before account lockout',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'lockout_duration_minutes',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Duration of account lockout in minutes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'enable_login_logging',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable logging of all login attempts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'enable_account_lockout',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable automatic account lockout after failed attempts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'session_timeout_minutes',
                'value' => '120',
                'type' => 'integer',
                'description' => 'Session timeout in minutes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_settings');
    }
};
