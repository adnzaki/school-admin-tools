<?php

namespace App\Controllers;

use App\Models\PengantarNisnModel;
use App\Models\SuratKeluarModel;
use App\Models\DataInstitusiModel;
use App\Models\SiswaModel;

class PengantarNISN extends BaseController
{
    use Traits\SuratTrait;

    private $model;

    private $letterId;

    private $suratKeluarModel;

    private $dataInstitusiModel;

    public function __construct()
    {
        $this->model = new PengantarNisnModel();
        $this->suratKeluarModel = new SuratKeluarModel();
        $this->dataInstitusiModel = new DataInstitusiModel();

        // initialize SuratTrait
        $this->initialize($this->suratKeluarModel, 'keluar');

        // activate helper first
        helper('sakola');

        $this->letterId = decrypt(request()->getGet('id'), env('encryption_key'));
    }

    public function getData()
    {
        $limit     = (int)$this->request->getPost('limit');
        $offset    = (int)$this->request->getPost('offset');
        $orderBy   = $this->request->getPost('orderBy');
        $sort      = $this->request->getPost('sort');
        $keyword    = $this->request->getPost('search');

        $builder = $this->model->withSiswaAndSurat()
            ->search($keyword)
            ->orderBy($orderBy, $sort);

        $totalRows = $builder->countAllResults(false);
        $container = $builder->findAll($limit, $offset);

        foreach ($container as $key => $value) {
            $container[$key]['tgl_surat'] = osdate()->create($value['tgl_surat'], 'd-M-y');
            $container[$key]['id'] = encrypt($value['id'], env('encryption_key'));
        }

        return $this->response->setJSON([
            'totalRows' => $totalRows,
            'container' => $container,
        ]);
    }

    public function getDetail(string $id)
    {
        $id = decrypt($id, env('encryption_key'));

        $data = $this->model->withSiswaAndSurat()
            ->where('tb_pengantar_nisn.id', $id)
            ->first();

        return $this->response->setJSON($data);
    }

    public function createSuratPengantarNISN()
    {
        if ($this->institusiId === null || !$this->letterId) {
            $message = 'Surat Pengantar NISN tidak ditemukan. <br/>' . $this->notfoundReason;
            return view('surat_notfound', ['message' => $message]);
        }

        $pdf = new \PDFCreator([
            'paperSize' => 'F4',
        ]);

        $institusi = $this->dataInstitusiModel->getWithInstitusi($this->institusiId);
        $title = 'Surat Pengantar NISN';
        $data = $this->model->withSiswaAndSurat()
            ->where('tb_pengantar_nisn.id', $this->letterId)
            ->first();

        $contentData = [
            'title'         => $title,
            'letterNumber'  => $data['no_surat'],
            'schoolName'    => $institusi['nama_sekolah'],
            'district'      => $institusi['kecamatan'],
            'city'          => $institusi['kab_kota'],
            'province'      => $institusi['provinsi'],
            'letter'        => $data,
            'date'          => osdate()->create($data['tgl_surat']),
        ];

        $data = [
            'pageTitle' => $title,
            'content'   => view('surat-siswa/pengantar_nisn', $contentData),
            'institusi' => $institusi
        ];

        $html = view('layout/main', $data);
        $pdf->loadHTML($html)->render()->stream('Surat-Pengantar-NISN.pdf');
    }

    public function delete()
    {
        $ids = $this->request->getJSON(true);

        if (empty($ids)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        // karena ID dikirim dalam bentuk terenkripsi, maka perlu didekripsi dulu
        // format array yg harus diolah adalah [{"id": "encrypted_id1"}, {"id": "encrypted_id2"}, ...]
        $ids = array_map(fn($item) => decrypt($item, env('encryption_key')), $ids);

        // Validasi: pastikan semua ID siswa tersedia
        $existing = $this->model->whereIn('id', $ids)->findAll();

        if (count($existing) !== count($ids)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        // ambil data surat keluar terkait
        $suratIds = array_map(fn($item) => $item['surat_id'], $existing);

        // hapus data pengantar nisn
        $this->model->whereIn('id', $ids)->delete($ids, true);

        // hapus data surat keluar terkait
        $this->deleteSurat($suratIds, true);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted')
        ]);
    }

    public function save()
    {
        $rules = [
            'id'            => ['rules' => 'permit_empty', 'label' => 'ID'],
            'siswa_id'      => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.nama')],
            'nomor_surat'   => ['rules' => 'required', 'label' => lang('FieldLabels.suratKeluar.nomor_surat')],
            'tgl_surat'     => ['rules' => 'required|valid_date', 'label' => lang('FieldLabels.suratKeluar.tgl_surat')],
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
        $suratId = $this->request->getPost('surat_id');
        $siswaId = $this->request->getPost('siswa_id');

        $data = [
            'siswa_id'      => $siswaId,
            'nomor_surat'   => $this->request->getPost('nomor_surat'),
            'tgl_surat'     => $this->request->getPost('tgl_surat'),
        ];

        // simpan data surat keluar dulu
        $institusiDetail = $this->dataInstitusiModel->where('institusi_id', get_institusi())->first();
        $siswaModel = new SiswaModel();
        $siswaDetail = $siswaModel->find($siswaId);

        $suratKeluarValues = [
            'institusi_id'  => get_institusi(),
            'nomor_surat'   => $data['nomor_surat'],
            'tujuan_surat'  => 'Dinas Pendidikan <br/>' . $institusiDetail['kab_kota'],
            'perihal'       => 'Permohonan Pengantar NISN',
            'tgl_surat'     => $data['tgl_surat'],
            'keterangan'    => $siswaDetail['nama'] . ' (' . $siswaDetail['nisn'] . ')',
            'relasi_tabel'  => 'tb_pengantar_nisn',
        ];

        if ($suratId) {
            $suratKeluarValues['id'] = $suratId;
        }

        $this->suratKeluarModel->save($suratKeluarValues);

        $suratNisnValues = [
            'siswa_id'  => $data['siswa_id'],
            'surat_id'  => $suratId ?? $this->suratKeluarModel->getInsertID(),
        ];

        if ($id) {
            $suratNisnValues['id'] = $id;
        }

        $this->model->save($suratNisnValues);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataSaved')
        ]);
    }
}
