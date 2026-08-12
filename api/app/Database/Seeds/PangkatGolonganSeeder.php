<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PangkatGolonganSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // PNS
            ['pangkat_golongan' => 'Juru Muda / I a', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Juru Muda Tk. I / I b', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Juru / I c', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Juru Tk. I / I d', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Pengatur Muda / II a', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Pengatur Muda Tk. I / II b', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Pengatur / II c', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Pengatur Tk. I / II d', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Penata Muda / III a', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Penata Muda Tk. I / III b', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Penata / III c', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Penata Tk. I / III d', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Pembina / IV a', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Pembina Tk. I / IV b', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Pembina Utama Muda / IV c', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Pembina Utama Madya / IV d', 'jenis_pegawai' => 'PNS'],
            ['pangkat_golongan' => 'Pembina Utama / IV e', 'jenis_pegawai' => 'PNS'],

            // PPPK
            ['pangkat_golongan' => 'I', 'jenis_pegawai' => 'PPPK'],
            ['pangkat_golongan' => 'IV', 'jenis_pegawai' => 'PPPK'],
            ['pangkat_golongan' => 'V', 'jenis_pegawai' => 'PPPK'],
            ['pangkat_golongan' => 'VI', 'jenis_pegawai' => 'PPPK'],
            ['pangkat_golongan' => 'VII', 'jenis_pegawai' => 'PPPK'],
            ['pangkat_golongan' => 'IX', 'jenis_pegawai' => 'PPPK'],
            ['pangkat_golongan' => 'X', 'jenis_pegawai' => 'PPPK'],
            ['pangkat_golongan' => 'XI', 'jenis_pegawai' => 'PPPK'],
        ];

        $this->db->table('tb_pangkat_golongan')->insertBatch($data);
    }
}
