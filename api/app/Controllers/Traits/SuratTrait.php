<?php

namespace App\Controllers\Traits;

use App\Models\LampiranSuratModel;
use App\Models\SuratMasukModel;
use App\Models\SuratKeluarModel;

trait SuratTrait
{
    /** @var LampiranSuratModel */
    protected $lampiranModel;

    /** nilai 'masuk' atau 'keluar' */
    protected $jenisSurat;

    /** @var SuratMasukModel|SuratKeluarModel */
    protected $suratModel;

    /**
     * Ambil semua lampiran berdasarkan ID surat dan jenis surat.
     */
    public function getLampiran($suratId)
    {
        $data = $this->lampiranModel
            ->where('jenis_surat', $this->jenisSurat)
            ->where('surat_id', $suratId)
            ->first();

        return $data;
    }

    public function uploadSuratPdf()
    {
        $config = [
            'file'    => 'surat',
            'dir'     => 'surat/' . $this->jenisSurat,
            'maxSize' => 4096,
            'prefix'  => 'surat_' . date('Ymd') . '_',
        ];

        $uploader = new \Uploader;
        $response = $uploader->uploadPdf($config);

        if ($response['msg'] !== 'OK') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $response['error'],
            ]);
        }

        // Ambil info filename dan path saja, tanpa simpan ke DB
        $uploaded = $response['uploaded'];
        return $this->response->setJSON([
            'status'   => 'success',
            'uploaded' => $uploaded
        ]);
    }

    public function delete()
    {
        $ids = $this->request->getJSON(true)['id'] ?? [];

        if (empty($ids)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        $existing = $this->suratModel->whereIn('id', $ids)->findAll();
        if (count($existing) !== count($ids)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        // Inisialisasi uploader
        $uploader = new \Uploader();

        // Ambil semua lampiran terkait
        $lampiran = $this->lampiranModel->whereIn('surat_id', $ids)->findAll();
        foreach ($lampiran as $file) {
            if (! empty($file['nama_file'])) {
                $filePath = 'surat/' . $this->jenisSurat . '/' . $file['nama_file'];
                $uploader->removeFile($filePath);
            }
        }

        // Hapus data lampiran di DB
        foreach ($lampiran as $data) {
            $this->lampiranModel->where('id', $data['id'])->delete($data['id']);
        }

        // Hapus data surat masuk
        $this->suratModel->whereIn('id', $ids)->delete($ids);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted')
        ]);
    }

    public function deleteBerkas()
    {
        $filename = $this->request->getPost('filename');
        $uploader = new \Uploader;
        $uploader->removeFile('surat/' . $this->jenisSurat . '/' . $filename);
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.fileDeleted')
        ]);
    }

    /**
     * Hapus berkas yang sudah tersimpan di database
     */
    public function deleteSavedBerkas()
    {
        $ids = $this->request->getJSON(true)['id'] ?? [];

        if (empty($ids)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        $existing = $this->lampiranModel->whereIn('id', $ids)->findAll();
        if (count($existing) !== count($ids)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        // Hapus file fisik
        $uploader = new \Uploader(); // sesuaikan jika namespace berbeda
        foreach ($existing as $lampiran) {
            if (! empty($lampiran['nama_file'])) {
                // $this->jenisSurat tergantung dari controller mana ia dipanggil: SuratMasuk atau SuratKeluar
                // $this->jenisSurat juga bisa diganti dengan $lampiran['jenis_surat']
                $filePath = 'surat/' . $this->jenisSurat . '/' . $lampiran['nama_file'];

                $uploader->removeFile($filePath);
            }
        }

        // Hapus dari database
        $this->lampiranModel->whereIn('id', $ids)->delete($ids);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted')
        ]);
    }
}
