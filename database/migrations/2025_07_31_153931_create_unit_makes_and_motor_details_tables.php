<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Table for unit makes (e.g. Honda, Yamaha)
        Schema::create('unit_makes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    
        // Table for motor details
        Schema::create('motor_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_application_id')->constrained()->onDelete('cascade');
            $table->enum('unit_type', ['motocab', 'tricycle']);
            $table->foreignId('unit_make_id')->constrained('unit_makes')->onDelete('cascade');
            $table->string('motorno');
            $table->string('chasisno');
            $table->string('platenumber');
            $table->timestamps();
        });
        
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_makes_and_motor_details_tables');
    }
};
