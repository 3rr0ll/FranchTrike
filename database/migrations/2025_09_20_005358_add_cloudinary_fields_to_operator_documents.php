<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('operator_documents', function (Blueprint $table) {
            $table->string('file_url')->nullable()->after('file_path');
            $table->string('cloudinary_public_id')->nullable()->after('file_url');
        });
    }
    
    public function down()
    {
        Schema::table('operator_documents', function (Blueprint $table) {
            $table->dropColumn(['file_url', 'cloudinary_public_id']);
        });
    }
    
};
