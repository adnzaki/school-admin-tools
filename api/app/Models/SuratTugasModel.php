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
        'surat_id',
        'tingkat_biaya',
        'tujuan',
        'transportasi',
        'lokasi',
        'durasi',
        'tgl_berangkat',
        'tgl_kembali',
        'kepala_skpd',
        'nip_kepala_skpd',
    ];

    public function withPegawaiAndSurat(): self
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

            // Kolom surat keluar dengan alias
            's.id             AS surat_id',
            's.institusi_id   AS surat_institusi_id',
            's.nomor_surat    AS surat_nomor_surat',
            's.tujuan_surat   AS surat_tujuan_surat',
            's.perihal        AS surat_perihal',
            's.tgl_surat      AS surat_tgl_surat',
            's.keterangan     AS surat_keterangan',
            's.relasi_tabel   AS surat_relasi_tabel',
            's.created_at     AS surat_created_at',
            's.updated_at     AS surat_updated_at',
            's.deleted_at     AS surat_deleted_at',
        ];

        return $this
            ->select($select)
            ->join('tb_pegawai p', 'p.id = tb_surat_tugas.pegawai_id', 'inner')
            ->join('tb_surat_keluar s', 's.id = tb_surat_tugas.surat_id', 'inner');
    }

    public function search(?string $keyword): self
    {
        if ($keyword) {
            $this->groupStart()
                ->like('tb_surat_tugas.tujuan', $keyword)
                ->orLike('p.nama', $keyword)
                ->orLike('s.nomor_surat', $keyword)
                ->groupEnd();
        }

        return $this;
    }
}
