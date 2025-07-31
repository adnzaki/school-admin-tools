<?php

namespace App\Controllers\Traits;

use App\Models\LampiranSuratModel;

trait LampiranSuratTrait
{
    /** @var LampiranSuratModel */
    protected $lampiranModel;

    /** nilai 'masuk' atau 'keluar' */
    protected $jenisSurat;

    /**
     * Ambil semua lampiran berdasarkan ID surat dan jenis surat.
     */
    public function getLampiran($suratId)
    {
        $data = $this->lampiranModel
            ->where('jenis_surat', $this->jenisSurat)
            ->where('surat_id', $suratId)
            ->findAll();

        return $this->response->setJSON([
            'status'    => 'success',
            'data'      => $data,
            'message'   => lang('General.dataFetched')
        ]);
    }

    /**
     * Simpan satu lampiran (insert/update).
     */
    public function saveLampiran()
    {
        $rules = [
            'surat_id'  => ['rules' => 'required|numeric', 'label' => lang('FieldLabels.lampiranSurat.surat_id')],
            'nama_file' => ['rules' => 'required',         'label' => lang('FieldLabels.lampiranSurat.nama_file')],
            'path'      => ['rules' => 'required',         'label' => lang('FieldLabels.lampiranSurat.path')],
        ];

        if (! $this->validate($rules)) {
            $errors   = $this->validator->getErrors();
            $messages = validation_error($errors, $rules);
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $messages
            ]);
        }

        $data = [
            'jenis_surat' => $this->jenisSurat,
            'surat_id'    => $this->request->getPost('surat_id'),
            'nama_file'   => $this->request->getPost('nama_file'),
            'path'        => $this->request->getPost('path'),
        ];

        if ($id = $this->request->getPost('id')) {
            $data['id'] = $id;
        }

        $this->lampiranModel->save($data);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataSaved')
        ]);
    }

    /**
     * Hapus lampiran berdasarkan ID batch.
     */
    public function deleteLampiran()
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

        $this->lampiranModel->whereIn('id', $ids)->delete();

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted')
        ]);
    }
}
