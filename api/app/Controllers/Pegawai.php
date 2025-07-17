<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;
use CodeIgniter\API\ResponseTrait;

class Pegawai extends BaseController
{
    use ResponseTrait;

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

        $data  = $this->pegawai->where('institusi_id', get_institusi())->orderBy($orderBy, $sort)->findAll($limit, $offset);
        $total = empty($search)
            ? $this->pegawai->where('institusi_id', get_institusi())->countAllResults()
            : $this->pegawai->where('institusi_id', get_institusi())->like($searchBy, $search)->countAllResults();

        return $this->response->setJSON([
            'container' => $data,
            'totalRows' => $total,
            'additionalResponse' => [
                'status'  => 'OK',
                'message' => lang('General.dataFetched')
            ]
        ]);
    }

    public function importData()
    {
        $default = [
            'institusi_id'  => get_institusi(),
            'nama'          => '',
            'nip'           => '',
            'jabatan'       => '',
            'jenis_pegawai' => '',
            'email'         => '',
            'telepon'       => '',
        ];

        $rules = [
            'nama' => [
                'rules' => 'required',
                'label' => lang('FieldLabels.pegawai.nama')
            ],
            'nip' => [
                'rules' => 'permit_empty|numeric|exact_length[18]|is_unique_nip[tb_pegawai.nip,id,{id}]',
                'label' => lang('FieldLabels.pegawai.nip')
            ],
            'jabatan' => [
                'rules' => 'permit_empty|max_length[50]',
                'label' => lang('FieldLabels.pegawai.jabatan')
            ],
            'jenis_pegawai' => [
                'rules' => 'required|in_list[PNS,PPPK,Honorer]',
                'label' => lang('FieldLabels.pegawai.jenis_pegawai')
            ],
            'email' => [
                'rules' => 'permit_empty|valid_email',
                'label' => lang('FieldLabels.pegawai.email')
            ],
            'telepon' => [
                'rules' => 'permit_empty|max_length[20]',
                'label' => lang('FieldLabels.pegawai.telepon')
            ]
        ];

        $result = import_spreadsheet($default, $rules, function ($rows) {
            $this->pegawai->insertBatch($rows);
        });

        return $this->response->setJSON($result);
    }



    public function save()
    {
        $rules = [
            'id' => [
                'rules' => 'permit_empty',
                'label' => 'ID'
            ],
            'nama' => [
                'rules' => 'required',
                'label' => lang('FieldLabels.pegawai.nama')
            ],
            'nip' => [
                'rules' => 'permit_empty|numeric|exact_length[18]|is_unique_nip[tb_pegawai.nip,id,{id}]',
                'label' => lang('FieldLabels.pegawai.nip')
            ],
            'jabatan' => [
                'rules' => 'permit_empty|max_length[50]',
                'label' => lang('FieldLabels.pegawai.jabatan')
            ],
            'jenis_pegawai' => [
                'rules' => 'required|in_list[PNS,PPPK,Honorer]',
                'label' => lang('FieldLabels.pegawai.jenis_pegawai')
            ],
            'email' => [
                'rules' => 'permit_empty|valid_email',
                'label' => lang('FieldLabels.pegawai.email')
            ],
            'telepon' => [
                'rules' => 'permit_empty|max_length[20]',
                'label' => lang('FieldLabels.pegawai.telepon')
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
            'institusi_id'  => get_institusi(),
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
                'message' => lang('General.dataNotFound')
            ]);
        }

        $this->pegawai->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted')
        ]);
    }

    public function detail($id = null)
    {
        $data = $this->pegawai->find($id);

        if (! $data) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $data
        ]);
    }
}
