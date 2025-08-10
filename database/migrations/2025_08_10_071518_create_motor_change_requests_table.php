<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up()
    {
        Schema::create('motor_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_application_id')->constrained()->onDelete('cascade');
            $table->string('old_unit_type');
            $table->unsignedBigInteger('old_unit_make_id');
            $table->string('old_motorno');
            $table->string('old_chasisno');
            $table->string('old_platenumber');

            $table->string('new_unit_type');
            $table->unsignedBigInteger('new_unit_make_id');
            $table->string('new_motorno');
            $table->string('new_chasisno');
            $table->string('new_platenumber');

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('motor_change_requests');
    }
};
