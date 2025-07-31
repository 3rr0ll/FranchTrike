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
        Schema::create('franchise_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number', 50)->nullable()->unique();
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('application_type', ['new', 'renewal'])->default('new');
            $table->foreignId('previous_application_id')->nullable()->constrained('franchise_applications')->onDelete('set null');

            $table->string('franchise_no', 50)->nullable();
            $table->string('sticker_no', 50)->nullable();
            $table->string('operator_name');

            $table->string('ctc_no', 50)->nullable();
            $table->date('ctc_date_issued')->nullable();
            $table->string('ctc_place_issued', 100)->nullable();

            $table->enum('status', [ 'submitted', 'under_review', 'approved', 'rejected'])->default('submitted');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');

            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->date('franchise_start_date')->nullable();
            $table->date('franchise_end_date')->nullable();
            $table->decimal('franchise_fee', 10, 2)->nullable();

            $table->timestamps();

            $table->index('status', 'idx_application_status');
            $table->index('operator_id', 'idx_application_operator');
            $table->index(['franchise_start_date', 'franchise_end_date'], 'idx_franchise_validity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_applications');
    }
};
