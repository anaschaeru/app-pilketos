<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // 1. Buat Akun ADMIN
    User::create([
      'name'      => 'Kesiswaan',
      'nisn'      => '80808080', // Gunakan angka unik untuk admin
      'kelas'     => 'Staff', // Isi sembarang untuk admin
      'password'  => Hash::make('admin80'), // Password login
      'role'      => 'admin',
      'has_voted' => false,
    ]);
  }
}
