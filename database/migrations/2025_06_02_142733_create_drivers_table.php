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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('driver_id', 50)->unique()->nullable();
            $table->string('last_name', 100);
            $table->string('first_name', 100);
            $table->string('middle_initial', 5)->nullable();
            $table->string('barangay', 100);
            $table->string('municipality', 100);
            $table->string('province', 100);
            $table->date('birth_date');
            $table->unsignedTinyInteger('age');
            $table->enum('sex', ['Male', 'Female']);
            $table->string('civil_status', 50);
            $table->string('contact_no', 20)->nullable();
            $table->string('license_no', 50)->unique();
            $table->date('license_validity')->nullable();
            $table->string('license_nature', 50)->nullable();
            $table->timestamps();

            $table->index(['last_name', 'first_name'], 'idx_driver_name');
            $table->index(['municipality', 'province'], 'idx_driver_location');
            $table->index('license_validity', 'idx_license_validity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
