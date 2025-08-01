<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Traits\LampiranSuratTrait;
use App\Models\SuratMasukModel;

class SuratMasuk extends BaseController
{
    use LampiranSuratTrait;

    protected $suratModel;

    public function __construct()
    {
        $this->suratModel    = new SuratMasukModel();
        $this->lampiranModel = new \App\Models\LampiranSuratModel();
        $this->jenisSurat    = 'masuk';
    }

    /** Server‐side pagination, search & sort */
    public function getData()
    {
        $limit     = (int)$this->request->getPost('limit');
        $offset    = (int)$this->request->getPost('offset');
        $orderBy   = $this->request->getPost('orderBy');
        $searchBy  = $this->request->getPost('searchBy');
        $sort      = $this->request->getPost('sort');
        $search    = $this->request->getPost('search');

        if (! empty($search)) {
            $this->suratModel->like($searchBy, $search);
        }

        $data  = $this->suratModel
            ->where('institusi_id', get_institusi())
            ->orderBy($orderBy, $sort)
            ->findAll($limit, $offset);

        $total = empty($search)
            ? $this->suratModel->where('institusi_id', get_institusi())->countAllResults()
            : $this->suratModel->where('institusi_id', get_institusi())->like($searchBy, $search)->countAllResults();

        return $this->response->setJSON([
            'container' => $data,
            'totalRows' => $total,
            'additionalResponse' => [
                'status'  => 'OK',
                'message' => lang('General.dataFetched')
            ]
        ]);
    }

    public function save()
    {
        $rules = [
            'id'           => ['rules' => 'permit_empty',           'label' => 'ID'],
            'nomor_surat'  => ['rules' => 'required',               'label' => lang('FieldLabels.suratMasuk.nomor_surat')],
            'asal_surat'   => ['rules' => 'required',               'label' => lang('FieldLabels.suratMasuk.asal_surat')],
            'perihal'      => ['rules' => 'required',               'label' => lang('FieldLabels.suratMasuk.perihal')],
            'tgl_surat'    => ['rules' => 'required|valid_date',    'label' => lang('FieldLabels.suratMasuk.tgl_surat')],
            'tgl_diterima' => ['rules' => 'required|valid_date',    'label' => lang('FieldLabels.suratMasuk.tgl_diterima')],
        ];

        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => validation_error($errors, $rules)
            ]);
        }

        $p = $this->request->getPost();
        $data = [
            'institusi_id' => get_institusi(),
            'nomor_surat'  => $p['nomor_surat'],
            'asal_surat'   => $p['asal_surat'],
            'perihal'      => $p['perihal'],
            'tgl_surat'    => $p['tgl_surat'],
            'tgl_diterima' => $p['tgl_diterima'],
            'keterangan'   => $p['keterangan'],
        ];

        if (! empty($p['id'])) {
            $data['id'] = $p['id'];
        }

        $this->suratModel->save($data);

        // Ambil ID surat yg baru disimpan
        $suratId = isset($data['id']) ? $data['id'] : $this->suratModel->getInsertID();

        // Jika filename tersedia, simpan sebagai lampiran surat masuk
        if (! empty($p['berkas'])) {
            $this->lampiranModel->save([
                'jenis_surat' => $this->jenisSurat,
                'surat_id'    => $suratId,
                'nama_file'   => $p['berkas'],
                'path'        => 'api/public/uploads/surat/masuk/' . $p['berkas'],
            ]);
        }


        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataSaved')
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

        // Hapus data surat masuk/keluar
        $this->suratModel->whereIn('id', $ids)->delete($ids);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted')
        ]);
    }

    public function detail(?int $id = null)
    {
        $data = $this->suratModel->find($id);

        if (! $data) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'success',
            'data'      => $data,
            'lampiran'  => $this->getLampiran($id),
        ]);
    }
}
