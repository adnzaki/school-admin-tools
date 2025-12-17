<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'tb_siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'institusi_id',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'no_induk',
        'nisn',
        'jenis_kelamin',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kab_kota',
        'provinsi',
        'cpd',
        'mutasi',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    public function findActiveStudent()
    {
        return $this->where(['institusi_id' => get_institusi(), 'cpd' => 0, 'mutasi' => 0]);
    }

    public function search(?string $keyword): self
    {
        if ($keyword) {
            $this->groupStart()
                ->like('nama', $keyword)
                ->orLike('no_induk', $keyword)
                ->orLike('nisn', $keyword)
                ->orLike('nama_ayah', $keyword)
                ->orLike('nama_ibu', $keyword)
                ->groupEnd();
        }

        return $this;
    }
}
