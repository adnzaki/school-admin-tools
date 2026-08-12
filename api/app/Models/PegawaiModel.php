<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table            = 'tb_pegawai';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'institusi_id',
        'pangkat_golongan_id',
        'nama',
        'nip',
        'jabatan',
        'jenis_pegawai',
        'email',
        'telepon'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    // join with tb_pangkat_golongan table
    public function withPangkatGolongan()
    {
        $this->select('tb_pegawai.*, tb_pangkat_golongan.pangkat_golongan');
        $this->join('tb_pangkat_golongan', 'tb_pegawai.pangkat_golongan_id = tb_pangkat_golongan.id', 'left');
        return $this;
    }
}
