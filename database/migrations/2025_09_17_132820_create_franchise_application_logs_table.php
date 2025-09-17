<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('franchise_application_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_application_id');
            $table->string('status_before')->nullable();
            $table->string('status_after');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('franchise_application_id')
                ->references('id')
                ->on('franchise_applications')
                ->onDelete('cascade');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franchise_application_logs');
    }
};
