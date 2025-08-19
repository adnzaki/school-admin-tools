<?php

namespace App\Models;

use CodeIgniter\Model;

class PindahSekolahModel extends Model
{
    protected $table            = 'tb_pindah_sekolah';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'siswa_id',
        'no_surat',
        'kelas',
        'sd_tujuan',
        'kelurahan',
        'kecamatan',
        'kab_kota',
        'provinsi',
        'alasan',
        'pindah_rayon',
        'tgl_pindah',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Builder dengan join ke tb_siswa.
     *
     * Catatan: gunakan method ini di awal chain sebelum where/filter lain.
     *
     * @return self
     */
    public function withSiswa(): self
    {
        $select = [
            'tb_pindah_sekolah.*',

            // Semua kolom siswa dengan alias
            's.id              AS siswa_id',
            's.institusi_id    AS siswa_institusi_id',
            's.nama            AS siswa_nama',
            's.tempat_lahir    AS siswa_tempat_lahir',
            's.tgl_lahir       AS siswa_tgl_lahir',
            's.no_induk        AS siswa_no_induk',
            's.nisn            AS siswa_nisn',
            's.jenis_kelamin   AS siswa_jenis_kelamin',
            's.nama_ayah       AS siswa_nama_ayah',
            's.pekerjaan_ayah  AS siswa_pekerjaan_ayah',
            's.nama_ibu        AS siswa_nama_ibu',
            's.pekerjaan_ibu   AS siswa_pekerjaan_ibu',
            's.alamat          AS siswa_alamat',
            's.rt              AS siswa_rt',
            's.rw              AS siswa_rw',
            's.kelurahan       AS siswa_kelurahan',
            's.kecamatan       AS siswa_kecamatan',
            's.kab_kota        AS siswa_kab_kota',
            's.provinsi        AS siswa_provinsi',
            's.created_at      AS siswa_created_at',
            's.updated_at      AS siswa_updated_at',
            's.deleted_at      AS siswa_deleted_at',
        ];

        return $this
            ->select($select)
            ->join('tb_siswa s', 's.id = tb_pindah_sekolah.siswa_id', 'inner');
    }

    /**
     * Ambil satu record berdasarkan id (dengan data siswa).
     *
     * @param int $id ID pindah sekolah
     * @return array|null Record pindah sekolah
     */
    public function findByIdWithSiswa(int $id): ?array
    {
        return $this->withSiswa()
            ->where('tb_pindah_sekolah.id', $id)
            ->first();
    }

    /**
     * Ambil banyak record (dengan data siswa).
     *
     * @param int $limit Jumlah record yang akan diambil. Nilai 0 berarti tidak ada batasan.
     * @param int $offset Jumlah record yang akan di-skip sebelum mengambil nilai. Nilai 0 berarti
     *                    mengambil dari awal.
     * @return array Record pindah sekolah dalam array
     */
    public function findAllWithSiswa(int $limit = 0, int $offset = 0): array
    {
        return $this->withSiswa()
            ->orderBy('tb_pindah_sekolah.created_at', 'DESC')
            ->findAll($limit, $offset);
    }

    /**
     * Menerapkan filter umum untuk kebutuhan multi-tenant dan pencarian ringan.
     * Method ini harus dipanggil setelah withSiswa() untuk menerapkan filter tambahan pada query.
     *
     * @param string|null $search Kata kunci pencarian untuk memfilter berdasarkan nama siswa atau dokumen.
     * @param string|null $kelas Kelas yang digunakan sebagai filter data.
     * @param string|null $tglStart Tanggal awal untuk filter berdasarkan 'tgl_pindah'.
     * @param string|null $tglEnd Tanggal akhir untuk filter berdasarkan 'tgl_pindah'.
     * @return self Mengembalikan instance query builder saat ini untuk chaining method.
     */
    public function applyFilters(?string $search = null, ?string $kelas = null, ?string $tglStart = null, ?string $tglEnd = null): self
    {
        $institusiId = get_institusi(); // langsung ambil dari helper, wajib ada

        $this->where('s.institusi_id', $institusiId);

        if (!empty($kelas)) {
            $this->where('tb_pindah_sekolah.kelas', $kelas);
        }

        if (!empty($search)) {
            $this->groupStart()
                ->like('s.nama', $search)
                ->orLike('tb_pindah_sekolah.no_surat', $search)
                ->orLike('tb_pindah_sekolah.sd_tujuan', $search)
                ->groupEnd();
        }

        if ($tglStart && $tglEnd) {
            $this->where('tb_pindah_sekolah.tgl_pindah >=', $tglStart)
                ->where('tb_pindah_sekolah.tgl_pindah <=', $tglEnd);
        } elseif ($tglStart) {
            $this->where('tb_pindah_sekolah.tgl_pindah >=', $tglStart);
        } elseif ($tglEnd) {
            $this->where('tb_pindah_sekolah.tgl_pindah <=', $tglEnd);
        }

        return $this;
    }
}
