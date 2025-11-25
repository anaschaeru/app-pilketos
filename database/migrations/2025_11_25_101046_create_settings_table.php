<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('settings', function (Blueprint $table) {
      $table->id();
      $table->boolean('voting_active')->default(false); // Default Mati (Off)
      $table->timestamps();
    });

    // Langsung isi data awal agar tidak error saat dipanggil pertama kali
    DB::table('settings')->insert([
      'voting_active' => false,
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('settings');
  }
};
