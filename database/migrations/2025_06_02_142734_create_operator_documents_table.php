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
        Schema::create('operator_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->foreignId('franchise_application_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('document_type_id')->constrained()->onDelete('restrict');
            $table->string('document_name');
            $table->string('file_path', 500);
            $table->string('file_type');
            $table->integer('file_size');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('upload_date')->useCurrent();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['operator_id', 'document_type_id'], 'idx_operator_documents');
            $table->index('status', 'idx_document_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operator_documents');
    }
};
