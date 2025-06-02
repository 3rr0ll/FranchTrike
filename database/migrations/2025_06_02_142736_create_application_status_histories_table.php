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
        Schema::create('application_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_application_id')->constrained()->onDelete('cascade');
            $table->enum('previous_status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])->nullable();
            $table->enum('new_status', ['draft', 'submitted', 'under_review', 'approved', 'rejected']);
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('change_reason')->nullable();
            $table->timestamp('changed_at')->useCurrent();

            $table->index(['franchise_application_id', 'changed_at'], 'idx_status_history');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_status_histories');
    }
};
