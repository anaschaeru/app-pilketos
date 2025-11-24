<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vote extends Model
{
    use HasFactory;

    // Tambahkan bagian ini agar data bisa disimpan
    protected $fillable = [
        'user_id',
        'candidate_id',
    ];

    // // Opsional: Tambahkan relasi jika nanti butuh mengambil data user/kandidat dari tabel vote
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function candidate()
    // {
    //     return $this->belongsTo(Candidate::class);
    // }
}
