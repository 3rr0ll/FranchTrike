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
        Schema::table('motor_details', function (Blueprint $table) {
            $table->string('franchise_number')->nullable()->after('platenumber');
            $table->string('sticker_number')->nullable()->after('franchise_number');
            $table->string('case_number')->nullable()->after('sticker_number');
            $table->string('or_number')->nullable()->after('case_number');
            $table->decimal('amount', 10, 2)->nullable()->after('or_number');
            $table->date('date_issued')->nullable()->after('amount');
            $table->string('place_issued')->nullable()->after('date_issued');
            $table->date('validity')->nullable()->after('place_issued');
            $table->string('toda_president')->nullable()->after('validity');
            $table->string('traffic_division')->nullable()->after('toda_president');
            $table->string('pfuc_chairperson')->nullable()->after('traffic_division');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('motor_details', function (Blueprint $table) {
            $table->dropColumn([
                'franchise_number',
                'sticker_number',
                'case_number',
                'or_number',
                'amount',
                'date_issued',
                'place_issued',
                'validity',
                'toda_president',
                'traffic_division',
                'pfuc_chairperson'
            ]);
        });
    }
};
