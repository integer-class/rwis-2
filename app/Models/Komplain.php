<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komplain extends Model
{
    use HasFactory;
    protected $table = 'komplain';

    //fillable
    protected $fillable = [
        'nik',
        'isi_komplain',
        'status_komplain',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'nik', 'nik');
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
    }


    public function rt()
    {
        return $this->belongsTo(Penduduk::class, 'id_rt', 'id');
    }


   

    


}
