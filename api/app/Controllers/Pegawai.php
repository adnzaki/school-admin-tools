<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;

class Pegawai extends BaseController
{
    protected $pegawai;

    public function __construct()
    {
        $this->pegawai = new PegawaiModel();
    }

    public function getData()
    {
        $limit     = (int)$this->request->getPost('limit');
        $offset    = (int)$this->request->getPost('offset');
        $orderBy   = $this->request->getPost('orderBy');
        $searchBy  = $this->request->getPost('searchBy');
        $sort      = $this->request->getPost('sort');
        $search    = $this->request->getPost('search');

        if (! empty($search)) {
            $this->pegawai->like($searchBy, $search);
        }

        $data  = $this->pegawai->orderBy($orderBy, $sort)->findAll($limit, $offset);
        $total = empty($search)
            ? $this->pegawai->countAllResults()
            : $this->pegawai->like($searchBy, $search)->countAllResults();

        return $this->response->setJSON([
            'container' => $data,
            'totalRows' => $total,
            'additionalResponse' => [
                'status'  => 'OK',
                'message' => 'Data berhasil diambil'
            ]
        ]);
    }

    public function save()
    {
        $rules = [
            'nama' => [
                'rules' => 'required',
                'label' => lang('FieldLabels.nama')
            ],
            'nip' => [
                'rules' => 'permit_empty|numeric|exact_length[18]',
                'label' => lang('FieldLabels.nip')
            ],
            'jabatan' => [
                'rules' => 'permit_empty|max_length[50]',
                'label' => lang('FieldLabels.jabatan')
            ],
            'jenis_pegawai' => [
                'rules' => 'required|in_list[PNS,PPPK,Honorer]',
                'label' => lang('FieldLabels.jenis_pegawai')
            ],
            'email' => [
                'rules' => 'permit_empty|valid_email',
                'label' => lang('FieldLabels.email')
            ],
            'telepon' => [
                'rules' => 'permit_empty|max_length[20]',
                'label' => lang('FieldLabels.telepon')
            ]
        ];

        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();

            $messages = validation_error($errors, $rules);

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $messages
            ]);
        }

        $id = $this->request->getPost('id');

        $data = [
            'nama'          => $this->request->getPost('nama'),
            'nip'           => $this->request->getPost('nip'),
            'jabatan'       => $this->request->getPost('jabatan'),
            'jenis_pegawai' => $this->request->getPost('jenis_pegawai'),
            'email'         => $this->request->getPost('email'),
            'telepon'       => $this->request->getPost('telepon'),
        ];

        if ($id) {
            $data['id'] = $id;
        }

        $this->pegawai->save($data);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataSaved')
        ]);
    }



    public function delete($id = null)
    {
        if (! $id || ! $this->pegawai->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $this->pegawai->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function detail($id = null)
    {
        $data = $this->pegawai->find($id);

        if (! $data) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $data
        ]);
    }
}
