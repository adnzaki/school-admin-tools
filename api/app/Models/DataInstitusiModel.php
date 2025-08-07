<?php

namespace App\Models;

use CodeIgniter\Model;

class DataInstitusiModel extends Model
{
    protected $table            = 'tb_data_institusi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'institusi_id',
        'kepala_sekolah',
        'nip_kepala_sekolah',
        'wakil_kepala_sekolah',
        'nip_wakil_kepala_sekolah',
        'bendahara_bos',
        'nip_bendahara_bos',
        'bendahara_barang',
        'nip_bendahara_barang',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kab_kota',
        'provinsi',
        'file_kop',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getWithInstitusi($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
        tb_data_institusi.*,
        tb_institusi.nama_sekolah,
        tb_institusi.penanggung_jawab,
        tb_institusi.no_kontak,
        tb_institusi.active,
        tb_institusi.masa_aktif,
        tb_institusi.tgl_registrasi
    ');
        $builder->join('tb_institusi', 'tb_institusi.id = tb_data_institusi.institusi_id');

        if ($id !== null) {
            $builder->where('tb_data_institusi.id', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }
}
