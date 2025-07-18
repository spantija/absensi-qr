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
    Schema::table('attendances', function (Blueprint $table) {
        $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('attendances', function (Blueprint $table) {
        $table->dropForeign(['subject_id']);
        $table->dropColumn('subject_id');
    });
}

};
