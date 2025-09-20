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
        Schema::table('driver_documents', function (Blueprint $table) {
            $table->string('file_url')->nullable()->after('file_path');
            $table->string('cloudinary_public_id')->nullable()->after('file_url');
        });
    }
    
    public function down()
    {
        Schema::table('driver_documents', function (Blueprint $table) {
            $table->dropColumn(['file_url', 'cloudinary_public_id']);
        });
    }
    
};
