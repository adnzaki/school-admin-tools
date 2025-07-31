<?php

namespace App\Models;

use CodeIgniter\Model;

class LampiranSuratModel extends Model
{
    protected $table            = 'tb_lampiran_surat';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'jenis_surat',
        'surat_id',
        'nama_file',
        'path'
    ];
    protected $useTimestamps    = true;
}
