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
        Schema::create('archived_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('document_type_id')->constrained()->onDelete('restrict');
            $table->foreignId('franchise_application_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('document_name');
            $table->string('file_path', 500);
            $table->string('file_type');
            $table->integer('file_size');
            $table->timestamp('archived_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_documents');
    }
};
