<?php

namespace App\Controllers;

use App\Models\SuratKeluarModel;
use App\Models\SuratMasukModel;
use App\Models\SiswaModel;
use App\Models\PegawaiModel;

class Home extends BaseController
{
    public function summary()
    {
        $suratKeluar = new SuratKeluarModel();
        $suratMasuk = new SuratMasukModel();
        $siswa = new SiswaModel();
        $pegawai = new PegawaiModel();

        return $this->response->setJSON([
            'suratKeluar' => $suratKeluar->where('institusi_id', get_institusi())->countAllResults(),
            'suratMasuk' => $suratMasuk->where('institusi_id', get_institusi())->countAllResults(),
            'siswa' => $siswa->findActiveStudent()->countAllResults(),
            'pegawai' => $pegawai->where('institusi_id', get_institusi())->countAllResults(),
        ]);
    }
}
