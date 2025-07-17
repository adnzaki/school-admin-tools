<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class InstitusiModel extends Model
{
    protected $table            = 'tb_institusi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'nama_sekolah',
        'penanggung_jawab',
        'no_kontak',
        'active',
        'masa_aktif',
        'tgl_registrasi',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function isInstitusiAktif(int $institusiId): bool
    {
        $institusi = $this->select('active, tgl_registrasi, masa_aktif')
            ->where('id', $institusiId)
            ->where('deleted_at', null)
            ->first();

        if (! $institusi || ! $institusi['active']) {
            return false;
        }

        $tglRegistrasi = new Time($institusi['tgl_registrasi']);
        $hariAktif     = (int) $institusi['masa_aktif'];
        $tanggalAkhir  = $tglRegistrasi->addDays($hariAktif);

        return Time::now()->isBefore($tanggalAkhir);
    }
}
