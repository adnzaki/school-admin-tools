<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SiswaModel;

class Siswa extends BaseController
{
    protected $siswa;

    public function __construct()
    {
        $this->siswa = new SiswaModel();
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
            $this->siswa->like($searchBy, $search);
        }

        $data  = $this->siswa->where('institusi_id', get_institusi())->orderBy($orderBy, $sort)->findAll($limit, $offset);
        $total = empty($search)
            ? $this->siswa->where('institusi_id', get_institusi())->countAllResults()
            : $this->siswa->where('institusi_id', get_institusi())->like($searchBy, $search)->countAllResults();

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
            'institusi_id'    => get_institusi(),
            'nama'            => '',
            'tempat_lahir'    => '',
            'tgl_lahir'       => '',
            'no_induk'        => '',
            'nisn'            => '',
            'jenis_kelamin'   => '',
            'nama_ayah'       => '',
            'pekerjaan_ayah'  => '',
            'nama_ibu'        => '',
            'pekerjaan_ibu'   => '',
            'alamat'          => '',
            'rt'              => '',
            'rw'              => '',
            'kelurahan'       => '',
            'kecamatan'       => '',
            'kab_kota'        => '',
            'provinsi'        => '',
        ];

        $rules = [
            'nama'            => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.nama')],
            'tempat_lahir'    => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.tempat_lahir')],
            'tgl_lahir'       => ['rules' => 'required|valid_date', 'label' => lang('FieldLabels.siswa.tgl_lahir')],
            'no_induk'        => ['rules' => 'permit_empty|numeric|exact_length[9]|is_unique_no_induk[tb_siswa.no_induk,id,{id}]', 'label' => lang('FieldLabels.siswa.no_induk')],
            'nisn'            => ['rules' => 'permit_empty|numeric|exact_length[10]|is_unique_nisn[tb_siswa.nisn,id,{id}]', 'label' => lang('FieldLabels.siswa.nisn')],
            'jenis_kelamin'   => ['rules' => 'required|in_list[L,P]', 'label' => lang('FieldLabels.siswa.jenis_kelamin')],
            'nama_ayah'       => ['rules' => 'permit_empty', 'label' => lang('FieldLabels.siswa.nama_ayah')],
            'pekerjaan_ayah'  => ['rules' => 'permit_empty', 'label' => lang('FieldLabels.siswa.pekerjaan_ayah')],
            'nama_ibu'        => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.nama_ibu')],
            'pekerjaan_ibu'   => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.pekerjaan_ibu')],
            'alamat'          => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.alamat')],
            'rt'              => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.rt')],
            'rw'              => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.rw')],
            'kelurahan'       => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.kelurahan')],
            'kecamatan'       => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.kecamatan')],
            'kab_kota'        => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.kab_kota')],
            'provinsi'        => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.provinsi')],
        ];

        $result = import_spreadsheet($default, $rules, function ($rows) {
            $this->siswa->insertBatch($rows);
        });

        return $this->response->setJSON($result);
    }


    public function save()
    {
        $rules = [
            'id'              => ['rules' => 'permit_empty', 'label' => 'ID'],
            'nama'            => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.nama')],
            'tempat_lahir'    => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.tempat_lahir')],
            'tgl_lahir'       => ['rules' => 'required|valid_date', 'label' => lang('FieldLabels.siswa.tgl_lahir')],
            'no_induk'        => ['rules' => 'permit_empty|numeric|exact_length[9]|is_unique_no_induk[tb_siswa.no_induk,id,{id}]', 'label' => lang('FieldLabels.siswa.no_induk')],
            'nisn'            => ['rules' => 'permit_empty|numeric|exact_length[10]|is_unique_nisn[tb_siswa.nisn,id,{id}]', 'label' => lang('FieldLabels.siswa.nisn')],
            'jenis_kelamin'   => ['rules' => 'required|in_list[L,P]', 'label' => lang('FieldLabels.siswa.jenis_kelamin')],
            'nama_ayah'       => ['rules' => 'permit_empty', 'label' => lang('FieldLabels.siswa.nama_ayah')],
            'pekerjaan_ayah'  => ['rules' => 'permit_empty', 'label' => lang('FieldLabels.siswa.pekerjaan_ayah')],
            'nama_ibu'        => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.nama_ibu')],
            'pekerjaan_ibu'   => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.pekerjaan_ibu')],
            'alamat'          => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.alamat')],
            'rt'              => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.rt')],
            'rw'              => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.rw')],
            'kelurahan'       => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.kelurahan')],
            'kecamatan'       => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.kecamatan')],
            'kab_kota'        => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.kab_kota')],
            'provinsi'        => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.provinsi')],
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
            'institusi_id'    => get_institusi(),
            'nama'            => $this->request->getPost('nama'),
            'tempat_lahir'    => $this->request->getPost('tempat_lahir'),
            'tgl_lahir'       => $this->request->getPost('tgl_lahir'),
            'no_induk'        => $this->request->getPost('no_induk'),
            'nisn'            => $this->request->getPost('nisn'),
            'jenis_kelamin'   => $this->request->getPost('jenis_kelamin'),
            'nama_ayah'       => $this->request->getPost('nama_ayah'),
            'pekerjaan_ayah'  => $this->request->getPost('pekerjaan_ayah'),
            'nama_ibu'        => $this->request->getPost('nama_ibu'),
            'pekerjaan_ibu'   => $this->request->getPost('pekerjaan_ibu'),
            'alamat'          => $this->request->getPost('alamat'),
            'rt'              => $this->request->getPost('rt'),
            'rw'              => $this->request->getPost('rw'),
            'kelurahan'       => $this->request->getPost('kelurahan'),
            'kecamatan'       => $this->request->getPost('kecamatan'),
            'kab_kota'        => $this->request->getPost('kab_kota'),
            'provinsi'        => $this->request->getPost('provinsi'),
        ];

        if ($id) {
            $data['id'] = $id;
        }

        $this->siswa->save($data);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataSaved')
        ]);
    }

    public function delete($id = null)
    {
        if (! $id || ! $this->siswa->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        $this->siswa->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted')
        ]);
    }

    public function detail($id = null)
    {
        $data = $this->siswa->find($id);

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
