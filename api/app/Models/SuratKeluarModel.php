<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratKeluarModel extends Model
{
    protected $table            = 'tb_surat_keluar';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'institusi_id',
        'nomor_surat',
        'tujuan_surat',
        'perihal',
        'tgl_surat',
        'keterangan',
        'relasi_tabel'
    ];
    protected $useTimestamps    = true;
}
