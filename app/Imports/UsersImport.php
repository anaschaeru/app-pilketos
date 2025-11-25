<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ToCollection; // Ganti ToModel jadi ToCollection

class UsersImport implements ToCollection, WithHeadingRow, WithChunkReading
{
  /**
   * Mengolah data sekaligus per-batch (Collection)
   */
  public function collection(Collection $rows)
  {
    // 1. Matikan pencatatan query agar memori hemat
    DB::disableQueryLog();

    $newUsers = [];
    $timestamp = now();

    // 2. Ambil list NISN dari excel untuk cek duplikat di RAM
    $excelNisns = $rows->pluck('nisn')->toArray();
    $existingNisns = User::whereIn('nisn', $excelNisns)->pluck('nisn')->toArray();

    foreach ($rows as $row) {
      // Validasi data kosong
      if (!isset($row['nama']) || !isset($row['nisn'])) continue;

      // Cek duplikat
      if (in_array($row['nisn'], $existingNisns)) continue;

      $newUsers[] = [
        'name'       => $row['nama'],
        'nisn'       => $row['nisn'],
        'kelas'      => $row['kelas'] ?? null,
        'role'       => 'siswa',
        'password'   => Hash::make($row['nisn']), // Ini proses berat!
        'has_voted'  => 0,
        'created_at' => $timestamp,
        'updated_at' => $timestamp,
      ];
    }

    // 3. LOGIKA PENYELAMAT:
    // Pecah lagi jadi sangat kecil (20 baris per kirim)
    // Agar paket data ringan
    $chunks = array_chunk($newUsers, 20);

    foreach ($chunks as $chunk) {
      try {
        // PAKSA SAMBUNG ULANG KE DATABASE
        // Ini akan membangunkan MySQL yang "tertidur" saat proses hashing tadi
        DB::reconnect();

        User::insert($chunk);
      } catch (\Exception $e) {
        // Jika masih gagal, coba sekali lagi (Retry logic)
        DB::reconnect();
        User::insert($chunk);
      }
    }
  }

  /**
   * BACA SEDIKIT DEMI SEDIKIT
   * Kita turunkan jadi 100 baris.
   * Artinya: Baca 100 Excel -> Hash -> Simpan -> Ulangi.
   * Ini mencegah MySQL menunggu terlalu lama.
   */
  public function chunkSize(): int
  {
    return 100;
  }
}
