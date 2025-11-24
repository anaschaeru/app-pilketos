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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_urut'); // Contoh: 01
            $table->string('nama_paslon'); // Contoh: "Budi & Siti"
            $table->text('visi');
            $table->text('misi');

            // Penambahan kolom baru
            $table->text('program_unggulan');

            $table->string('foto')->nullable(); // Path foto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
