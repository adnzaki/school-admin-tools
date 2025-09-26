<?php

namespace App\Models;

use CodeIgniter\Model;

class PengantarNisnModel extends Model
{
    protected $table = 'tb_pengantar_nisn';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['siswa_id', 'surat_id'];

    public function withSiswaAndSurat(): self
    {
        $this->select('tb_pengantar_nisn.*, s.nama AS siswa_nama, s.tempat_lahir AS tempat_lahir, s.tgl_lahir AS tgl_lahir, s.nisn AS siswa_nisn, s.alamat AS siswa_alamat, s.rt AS siswa_rt, s.rw AS siswa_rw, s.kelurahan AS siswa_kelurahan, s.kecamatan AS siswa_kecamatan, s.kab_kota AS siswa_kab_kota, sk.nomor_surat AS no_surat, sk.tgl_surat AS tgl_surat');
        $this->join('tb_siswa s', 's.id = tb_pengantar_nisn.siswa_id');
        $this->join('tb_surat_keluar sk', 'sk.id = tb_pengantar_nisn.surat_id');

        return $this;
    }

    public function search(?string $keyword = null): self
    {
        if (empty($keyword)) {
            return $this;
        }

        $this->where('sk.institusi_id', get_institusi());
        $this->groupStart();
        $this->like('s.nama', $keyword);
        $this->orLike('s.nisn', $keyword);
        $this->orLike('sk.nomor_surat', $keyword);
        $this->groupEnd();

        return $this;
    }
}
