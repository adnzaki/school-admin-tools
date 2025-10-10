<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DataInstitusiModel;

class DataInstitusi extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new DataInstitusiModel();
    }

    public function getDetail()
    {
        $institusiId = get_institusi(); // Asumsikan helper ini ambil ID institusi aktif

        $data = $this->model->getWithInstitusi($institusiId);

        if (! $data) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        $data['wakil_kepala_sekolah'] = $data['wakil_kepala_sekolah'] === null || $data['wakil_kepala_sekolah'] === 'null' ? '' : $data['wakil_kepala_sekolah'];
        $data['kop_path'] = base_url() . 'uploads/kop/' . $data['file_kop'];
        $data['nip_kepala_sekolah'] = formatNIP($data['nip_kepala_sekolah']);
        $data['nip_wakil_kepala_sekolah'] = formatNIP($data['nip_wakil_kepala_sekolah']);
        $data['nip_bendahara_bos'] = formatNIP($data['nip_bendahara_bos']);
        $data['nip_bendahara_barang'] = formatNIP($data['nip_bendahara_barang']);

        return $this->response->setJSON([
            'status'  => 'success',
            'data'    => $data,
            'message' => lang('General.dataFetched')
        ]);
    }

    public function uploadKop()
    {
        $config = [
            'file'    => 'kop',
            'dir'     => 'kop',
            'maxSize' => 2048,
            'prefix'  => 'kop_',
            'crop'    => 'resize',
            'width'   => 2400,
            'height'  => 460,
            'quality' => 100
        ];

        $uploader = new \Uploader;
        $response = $uploader->uploadImage($config);
        add_log('memperbarui kop sekolah');

        return $this->response->setJSON($response);
    }

    public function deleteKop()
    {
        $filename = $this->request->getPost('filename');
        $uploader = new \Uploader;
        $uploader->removeFile('kop/' . $filename);
        add_log('menghapus kop sekolah');

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.fileDeleted')
        ]);
    }

    public function save()
    {
        $rules = [
            'kepala_sekolah'           => ['rules' => 'required',         'label' => lang('FieldLabels.institusi.kepala_sekolah')],
            'nip_kepala_sekolah'       => ['rules' => 'required|exact_length[18]', 'label' => lang('FieldLabels.institusi.nip_kepala_sekolah')],
            'wakil_kepala_sekolah'     => ['rules' => 'permit_empty',     'label' => lang('FieldLabels.institusi.wakil_kepala_sekolah')],
            'nip_wakil_kepala_sekolah' => ['rules' => 'permit_empty|exact_length[18]', 'label' => lang('FieldLabels.institusi.nip_wakil_kepala_sekolah')],
            'bendahara_bos'            => ['rules' => 'required',         'label' => lang('FieldLabels.institusi.bendahara_bos')],
            'nip_bendahara_bos'        => ['rules' => 'required|exact_length[18]', 'label' => lang('FieldLabels.institusi.nip_bendahara_bos')],
            'bendahara_barang'         => ['rules' => 'required',         'label' => lang('FieldLabels.institusi.bendahara_barang')],
            'nip_bendahara_barang'     => ['rules' => 'required|exact_length[18]', 'label' => lang('FieldLabels.institusi.nip_bendahara_barang')],
            'alamat'                   => ['rules' => 'required',         'label' => lang('FieldLabels.institusi.alamat')],
            'kelurahan'                => ['rules' => 'required',         'label' => lang('FieldLabels.institusi.kelurahan')],
            'kecamatan'                => ['rules' => 'required',         'label' => lang('FieldLabels.institusi.kecamatan')],
            'kab_kota'                 => ['rules' => 'required',         'label' => lang('FieldLabels.institusi.kab_kota')],
            'provinsi'                 => ['rules' => 'required',         'label' => lang('FieldLabels.institusi.provinsi')],
            'file_kop'                 => ['rules' => 'permit_empty',     'label' => lang('FieldLabels.institusi.file_kop')],
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => validation_error($this->validator->getErrors(), $rules)
            ]);
        }

        $p = $this->request->getPost();
        $institusiId = get_institusi();

        $existing = $this->model
            ->where('institusi_id', $institusiId)
            ->first();

        if (! $existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        $data = [
            'id'                       => $existing['id'],
            'institusi_id'             => $institusiId,
            'kepala_sekolah'           => $p['kepala_sekolah'],
            'nip_kepala_sekolah'       => $p['nip_kepala_sekolah'],
            'wakil_kepala_sekolah'     => $p['wakil_kepala_sekolah'],
            'nip_wakil_kepala_sekolah' => $p['nip_wakil_kepala_sekolah'],
            'bendahara_bos'            => $p['bendahara_bos'],
            'nip_bendahara_bos'        => $p['nip_bendahara_bos'],
            'bendahara_barang'         => $p['bendahara_barang'],
            'nip_bendahara_barang'     => $p['nip_bendahara_barang'],
            'alamat'                   => $p['alamat'],
            'kelurahan'                => $p['kelurahan'],
            'kecamatan'                => $p['kecamatan'],
            'kab_kota'                 => $p['kab_kota'],
            'provinsi'                 => $p['provinsi'],
            'file_kop'                 => $p['file_kop'],
        ];

        if (! empty($p['file_kop'])) {
            $uploader = new \Uploader;
            $detail = $this->model->getWithInstitusi($institusiId);
            $uploader->removePreviousFile($detail['file_kop'], $p['file_kop'], 'kop/');
        }

        $this->model->save($data);
        add_log('memperbarui data sekolah');

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataSaved')
        ]);
    }
}
