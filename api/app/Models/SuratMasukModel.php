<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratMasukModel extends Model
{
    protected $table            = 'tb_surat_masuk';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'institusi_id',
        'nomor_surat',
        'asal_surat',
        'perihal',
        'tgl_surat',
        'tgl_diterima',
        'keterangan'
    ];
    protected $useTimestamps    = true;
}
