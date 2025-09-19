<?php

namespace App\Controllers\Traits;

use App\Models\SuratMasukModel;
use App\Models\SuratKeluarModel;
use App\Models\LampiranSuratModel;

trait SuratTrait
{
    /** @var LampiranSuratModel */
    protected $lampiranModel;

    /** nilai 'masuk' atau 'keluar' */
    protected $jenisSurat;

    /** @var SuratMasukModel|SuratKeluarModel */
    protected $suratModel;

    /** @var \CloudflareS3 */
    protected $cf;

    /**
     * Initialize the SuratTrait.
     *
     * @param SuratMasukModel|SuratKeluarModel $suratModel The SuratMasukModel or SuratKeluarModel instance.
     * @param string $jenisSurat The type of surat ('masuk' or 'keluar').
     */
    public function initialize($suratModel, $jenisSurat)
    {
        $this->suratModel    = $suratModel;
        $this->jenisSurat    = $jenisSurat;
        $this->lampiranModel = new LampiranSuratModel();
        $this->cf            = new \CloudflareS3('surat-' . $this->jenisSurat);
    }

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
        $this->cf->putObject($uploaded[0]['filepath'], 'application/pdf');
        $uploader->removeFile('surat/' . $this->jenisSurat . '/' . $uploaded[0]['filename']);

        return $this->response->setJSON([
            'status'   => 'success',
            'uploaded' => $uploaded,
            'url'      => $this->cf->getPresignedObjectUrl($uploaded[0]['filename']),
        ]);
    }

    public function deleteSurat($letterIds = [], $purge = false)
    {
        $ids = $letterIds ?: $this->request->getJSON(true)['id'] ?? [];

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

        // Ambil semua lampiran terkait
        $lampiran = $this->lampiranModel->whereIn('surat_id', $ids)->findAll();
        foreach ($lampiran as $file) {
            if (! empty($file['nama_file'])) {
                $this->cf->deleteObject($file['nama_file']);
            }
        }

        // Hapus data lampiran di DB
        foreach ($lampiran as $data) {
            $this->lampiranModel->where('id', $data['id'])->delete($data['id'], $purge);
        }

        // Hapus data surat masuk/keluar
        $this->suratModel->whereIn('id', $ids)->delete($ids, $purge);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted')
        ]);
    }

    public function deleteBerkas()
    {
        $filename = $this->request->getPost('filename');
        $this->cf->deleteObject($filename);
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
        foreach ($existing as $lampiran) {
            if (! empty($lampiran['nama_file'])) {
                $this->cf->deleteObject($lampiran['nama_file']);
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
