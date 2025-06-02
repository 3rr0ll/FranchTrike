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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->enum('applies_to', ['operator', 'driver', 'both']);
            $table->boolean('is_required')->default(true);
            $table->integer('max_file_size_mb')->default(5);
            $table->json('allowed_extensions')->default(json_encode(['pdf', 'jpg', 'jpeg', 'png']));
            $table->timestamps();

            $table->index('applies_to', 'idx_document_applies_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
