<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Traits\SuratTrait;
use App\Models\SuratMasukModel;
use App\Models\LampiranSuratModel;

class SuratMasuk extends BaseController
{
    use SuratTrait;

    public function __construct()
    {
        $this->suratModel    = new SuratMasukModel();
        $this->lampiranModel = new LampiranSuratModel();
        $this->jenisSurat    = 'masuk';
        $this->cf            = new \CloudflareS3('surat-' . $this->jenisSurat);
    }

    /** Server‐side pagination, search & sort */
    public function getData($dateStart = '', $dateEnd = '')
    {
        $limit     = (int)$this->request->getPost('limit');
        $offset    = (int)$this->request->getPost('offset');
        $orderBy   = $this->request->getPost('orderBy');
        // $searchBy  = $this->request->getPost('searchBy');
        $sort      = $this->request->getPost('sort');
        $search    = $this->request->getPost('search');

        $builder = $this->suratModel->search($search);

        if (! empty($dateStart) && ! empty($dateEnd)) {
            $builder->where('tgl_surat >=', $dateStart)->where('tgl_surat <=', $dateEnd);
        }

        $totalRows = $builder->countAllResults(false);
        $data = $builder->orderBy($orderBy, $sort)->orderBy('id', 'desc')->findAll($limit, $offset);

        return $this->response->setJSON([
            'container' => $data,
            'totalRows' => $totalRows,
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

        $archive = null;

        $logMessage = 'menambahkan surat masuk dengan nomor ' . $p['nomor_surat'];

        if (! empty($p['id'])) {
            $logMessage = str_replace('menambahkan', 'memperbarui', $logMessage);
            $archive = $this->getLampiran($p['id']);
            $data['id'] = $p['id'];
            if (! empty($p['berkas']) && $archive !== null) {
                $this->cf->removePreviousObject($archive['nama_file'], $p['berkas']);
            }
        }

        $this->suratModel->save($data);
        add_log($logMessage);

        // Ambil ID surat yg baru disimpan
        $suratId = isset($data['id']) ? $data['id'] : $this->suratModel->getInsertID();

        // Jika filename tersedia, simpan sebagai lampiran surat masuk
        if (! empty($p['berkas'])) {
            $archiveData = [
                'jenis_surat' => $this->jenisSurat,
                'surat_id'    => $suratId,
                'nama_file'   => $p['berkas'],
                'path'        => 'api/public/uploads/surat/masuk/' . $p['berkas'],
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

        $file = $this->getLampiran($id);
        if ($file !== null) {
            $file['file_url'] = $this->cf->getPresignedObjectUrl($file['nama_file']);
        }

        return $this->response->setJSON([
            'status'    => 'success',
            'data'      => $data,
            'lampiran'  => $file,
        ]);
    }
}
