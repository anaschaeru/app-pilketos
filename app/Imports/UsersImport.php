<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection; // Ganti ToModel jadi ToCollection
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToCollection, WithHeadingRow, WithChunkReading
{
  /**
   * Mengolah data sekaligus per-batch (Collection)
   */
  public function collection(Collection $rows)
  {
    $newUsers = [];
    $timestamp = now(); // Ambil waktu sekarang untuk created_at & updated_at

    // 1. Ambil semua NISN dari file Excel ini ke dalam array
    $excelNisns = $rows->pluck('nisn')->toArray();

    // 2. Ambil semua NISN yang SUDAH ADA di database (sekali query saja)
    // Tujuannya agar kita tidak perlu cek ke DB berulang-ulang di dalam loop
    $existingNisns = User::whereIn('nisn', $excelNisns)
      ->pluck('nisn')
      ->toArray();

    // 3. Looping data di memori (PHP) - Jauh lebih cepat dari Query DB
    foreach ($rows as $row) {

      // Skip jika nama/nisn kosong
      if (!isset($row['nama']) || !isset($row['nisn'])) continue;

      // Skip jika NISN sudah ada di database (Cek via array, bukan query)
      if (in_array($row['nisn'], $existingNisns)) continue;

      // Masukkan ke antrian array insert
      $newUsers[] = [
        'name'      => $row['nama'],
        'nisn'      => $row['nisn'],
        'kelas'     => $row['kelas'] ?? null,
        'role'      => 'siswa',
        'password'  => Hash::make($row['nisn']), // Hashing memang agak berat di CPU
        'has_voted' => 0, // False di database biasanya 0
        'created_at' => $timestamp, // Insert manual butuh timestamp manual
        'updated_at' => $timestamp,
      ];
    }

    // 4. Lakukan BULK INSERT (Simpan sekaligus)
    // Kita pecah per 500 data agar query tidak kepanjangan (SQL Error)
    $chunks = array_chunk($newUsers, 50);

    foreach ($chunks as $chunk) {
      User::insert($chunk);
    }
  }

  /**
   * Membaca file Excel sedikit demi sedikit agar RAM tidak jebol
   * Jika datamu > 5000, ini wajib.
   */
  public function chunkSize(): int
  {
    return 200; // Baca per 1000 baris excel
  }
}
