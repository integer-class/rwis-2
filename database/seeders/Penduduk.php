<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Penduduk extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Penduduk::factory()->create([
            'nik' => '1234567890123456',
            'nama' => 'Test User',
            'nomor_kk' => '472751886',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'L',
            'golong_darah' => 'A',
            'alamat' => 'Jl. Test No. 1',
            'agama' => 'Islam',
            'status_perkawinan' => 'Belum Kawin',
            'pekerjaan' => 'PNS',
            'id_rt' => '1',
            'foto' => 'default.png',
            'pendapatan' => 5000000,
            'status_sosial' => 'Lainnya',
            'status_rumah' => 'Milik',
            'status_kesehatan' => 'sehat',


        ]);

        \App\Models\Penduduk::factory()->create([
            'nik' => '1234567890123457',
            'nama' => 'Test User 2',
            'nomor_kk' => '472751886',
            'tanggal_lahir' => '2000-01-02',
            'jenis_kelamin' => 'P',
            'golong_darah' => 'B',
            'alamat' => 'Jl. Test No. 2',
            'agama' => 'Kristen',
            'status_perkawinan' => 'Kawin',
            'pekerjaan' => 'Wirausaha',
            'id_rt' => '1',
            'foto' => 'default.png',
            'pendapatan' => 2000000,
            'status_sosial' => 'yatimpiatu',
            'status_rumah' => 'Sewa',
            'status_kesehatan' => 'sehat',

        ]);

        \App\Models\Penduduk::factory()->create([
            'nik' => '1234567890123458',
            'nama' => 'Test User 3',
            'nomor_kk' => '472751887',
            'tanggal_lahir' => '2000-01-03',
            'jenis_kelamin' => 'L',
            'golong_darah' => 'AB',
            'alamat' => 'Jl. Test No. 3',
            'agama' => 'Hindu',
            'status_perkawinan' => 'Cerai',
            'pekerjaan' => 'Pengusaha',
            'id_rt' => '1',
            'foto' => 'default.png',
            'pendapatan' => 2000000,
            'status_sosial' => 'yatimpiatu',
            'status_rumah' => 'Sewa',
            'status_kesehatan' => 'sehat',


        ]);
    }
}
