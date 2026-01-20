<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratTugasModel extends Model
{
    protected $table            = 'tb_surat_tugas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    // Aktifkan soft deletes
    protected $useSoftDeletes   = true;

    // Aktifkan timestamps
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    // Field yang boleh diisi
    protected $allowedFields    = [
        'pegawai_id',
        'tingkat_biaya',
        'tujuan',
        'transportasi',
        'lokasi',
        'durasi',
        'tgl_berangkat',
        'tgl_kembali',
        'kepala_skpd',
        'jabatan_kepala_skpd',
        'nip_kepala_skpd',
        'sppd',
    ];

    public function withPegawai(): self
    {
        $select = [
            'tb_surat_tugas.*',

            // Kolom pegawai dengan alias
            'p.id             AS pegawai_id',
            'p.institusi_id   AS pegawai_institusi_id',
            'p.nama           AS pegawai_nama',
            'p.nip            AS pegawai_nip',
            'p.jabatan        AS pegawai_jabatan',
            'p.jenis_pegawai  AS pegawai_jenis_pegawai',
            'p.email          AS pegawai_email',
            'p.telepon        AS pegawai_telepon',
            'p.created_at     AS pegawai_created_at',
            'p.updated_at     AS pegawai_updated_at',
            'p.deleted_at     AS pegawai_deleted_at',
        ];

        return $this
            ->select($select)
            ->join('tb_pegawai p', 'p.id = tb_surat_tugas.pegawai_id', 'inner')
            ->where('p.institusi_id', get_institusi());
    }

    /**
     * Ambil satu record berdasarkan id (dengan data pegawai dan surat).
     *
     * @param int $id ID surat tugas
     * @return array|null Record surat tugas
     */
    public function findByIdWithPegawai(int $id): ?array
    {
        return $this->withPegawai()
            ->where('tb_surat_tugas.id', $id)
            ->first();
    }

    public function search(?string $keyword): self
    {
        if ($keyword) {
            $this->groupStart()
                ->like('tb_surat_tugas.tujuan', $keyword)
                ->orLike('p.nama', $keyword)
                ->groupEnd();
        }

        return $this;
    }
}
