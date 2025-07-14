<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        //Schema::table('students', function (Blueprint $table) {
            //$table->unsignedBigInteger('class_id')->nullable()->after('walimurid_id');
            //$table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
        //});
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }
};
