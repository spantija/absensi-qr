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
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained('users')->onDelete('cascade');

        $table->date('date'); // Tanggal absen
        $table->time('check_in')->nullable(); // Absen masuk
        $table->time('check_out')->nullable(); // Absen pulang

        $table->string('status')->nullable(); // hadir, izin, sakit, alpa (optional)
        $table->timestamps();

        $table->unique(['student_id', 'date']); // 1 baris per hari per siswa
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
