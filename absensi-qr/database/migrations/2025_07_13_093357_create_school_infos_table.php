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
    Schema::create('school_infos', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->date('date')->nullable(); // untuk kalender akademik atau pengumuman terjadwal
        $table->string('image')->nullable(); // opsional untuk gambar informasi
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_infos');
    }
};
