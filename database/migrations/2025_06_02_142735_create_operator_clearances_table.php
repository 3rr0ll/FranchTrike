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
        Schema::create('operator_clearances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->foreignId('franchise_application_id')->nullable()->constrained()->onDelete('cascade');

            $table->boolean('barangay_clearance')->default(false);
            $table->boolean('police_clearance')->default(false);
            $table->boolean('medical_certificate')->default(false);
            $table->boolean('drug_test')->default(false);
            $table->boolean('or_requirement')->default(false);
            $table->boolean('cr_requirement')->default(false);
            $table->boolean('ctc_requirement')->default(false);
            $table->boolean('old_mtop_mayors_permit')->default(false);
            $table->boolean('proof_of_ownership')->default(false);
            $table->boolean('cedula')->default(false);

            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();

            $table->unique(['operator_id', 'franchise_application_id'], 'unique_operator_clearance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operator_clearances');
    }
};
