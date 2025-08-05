<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Traits\SuratTrait;
use App\Models\SuratKeluarModel;
use App\Models\LampiranSuratModel;

class SuratKeluar extends BaseController
{
    use SuratTrait;

    public function __construct()
    {
        $this->suratModel    = new SuratKeluarModel();
        $this->lampiranModel = new LampiranSuratModel();
        $this->jenisSurat    = 'keluar';
    }

    public function getData()
    {
        $limit     = (int)$this->request->getPost('limit');
        $offset    = (int)$this->request->getPost('offset');
        $orderBy   = $this->request->getPost('orderBy');
        $searchBy  = $this->request->getPost('searchBy');
        $sort      = $this->request->getPost('sort');
        $search    = $this->request->getPost('search');

        $data  = $this->like($searchBy, $search)
            ->where('institusi_id', get_institusi())
            ->orderBy($orderBy, $sort)
            ->findAll($limit, $offset);

        $total = $this->like($searchBy, $search)->where('institusi_id', get_institusi())->countAllResults();

        return $this->response->setJSON([
            'container' => $data,
            'totalRows' => $total,
            'additionalResponse' => [
                'status'  => 'OK',
                'message' => lang('General.dataFetched')
            ]
        ]);
    }

    public function like($searchBy, $search)
    {
        if (! empty($search)) {
            if (strpos($searchBy, '-') !== false) {
                $searchBy = explode('-', $searchBy);
                $like1 = "($searchBy[0] LIKE '%$search%' ESCAPE '!' OR $searchBy[1]";
                $like2 = "'%$search%' ESCAPE '!')";
                return $this->suratModel->like($like1, $like2, 'none', false);
            } else {
                return $this->suratModel->like($searchBy, $search);
            }
        } else {
            return $this->suratModel;
        }
    }

    public function save()
    {
        $rules = [
            'id'           => ['rules' => 'permit_empty',               'label' => 'ID'],
            'nomor_surat'  => ['rules' => 'required',                   'label' => lang('FieldLabels.suratKeluar.nomor_surat')],
            'tujuan_surat' => ['rules' => 'required',                   'label' => lang('FieldLabels.suratKeluar.tujuan_surat')],
            'perihal'      => ['rules' => 'required',                   'label' => lang('FieldLabels.suratKeluar.perihal')],
            'tgl_surat'    => ['rules' => 'required|valid_date',        'label' => lang('FieldLabels.suratKeluar.tgl_surat')],
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
            'tujuan_surat' => $p['tujuan_surat'],
            'perihal'      => $p['perihal'],
            'tgl_surat'    => $p['tgl_surat'],
            'keterangan'   => $p['keterangan'],
        ];

        $archive = null;

        if (! empty($p['id'])) {
            $archive = $this->getLampiran($p['id']);
            $data['id'] = $p['id'];
            if (! empty($p['berkas']) && $archive !== null) {
                $uploader = new \Uploader;
                $uploader->removePreviousFile($archive['nama_file'], $p['berkas'], 'surat/keluar/');
            }
        }

        $this->suratModel->save($data);

        // Ambil ID surat yg baru disimpan
        $suratId = isset($data['id']) ? $data['id'] : $this->suratModel->getInsertID();

        // Jika filename tersedia, simpan sebagai lampiran surat keluar
        if (! empty($p['berkas'])) {
            $archiveData = [
                'jenis_surat' => $this->jenisSurat,
                'surat_id'    => $suratId,
                'nama_file'   => $p['berkas'],
                'path'        => 'api/public/uploads/surat/keluar/' . $p['berkas'],
            ];

            if ($archive !== null) {
                $archiveData['id'] = $archive['id'];
            }

            $this->lampiranModel->save($archiveData);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataSaved')
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
