<?php

namespace App\Controllers;

use App\Models\SuratTugasModel;
use App\Models\SuratKeluarModel;
use App\Models\DataInstitusiModel;
use App\Models\PegawaiModel;

class Sppd extends BaseController
{
    use Traits\SuratTrait;

    private $model;

    private $letterId;

    private $suratKeluarModel;

    private $dataInstitusiModel;

    private $pegawaiModel;

    public function __construct()
    {
        $this->model = new SuratTugasModel();
        $this->suratKeluarModel = new SuratKeluarModel();
        $this->dataInstitusiModel = new DataInstitusiModel();
        $this->pegawaiModel = new PegawaiModel();

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

        $builder = $this->model->withPegawaiAndSurat()
            ->search($keyword)
            ->orderBy($orderBy, $sort)
            ->orderBy('id', 'desc');

        $totalRows = $builder->countAllResults(false);
        $container = $builder->findAll($limit, $offset);

        foreach ($container as $key => $value) {
            $container[$key]['tgl_surat'] = osdate()->create($value['surat_tgl_surat'], 'd-M-y');
            $container[$key]['tgl_berangkat'] = osdate()->create($value['tgl_berangkat'], 'd-M-y');
            $container[$key]['tgl_kembali'] = osdate()->create($value['tgl_kembali'], 'd-M-y');
            $container[$key]['id'] = encrypt($value['id'], env('encryption_key'));
        }

        return $this->response->setJSON([
            'totalRows'     => $totalRows,
            'container'     => $container,
        ]);
    }

    public function getDetail(string $id)
    {
        $id = decrypt($id, env('encryption_key'));

        $data = $this->model->withPegawaiAndSurat()
            ->where('tb_surat_tugas.id', $id)
            ->first();

        return $this->response->setJSON($data);
    }

    public function createSuratTugas()
    {
        if ($this->institusiId === null || !$this->letterId) {
            $message = 'Surat Tugas ini tidak ditemukan. <br/>' . $this->notfoundReason;
            return view('surat_notfound', ['message' => $message]);
        }

        $pdf = new \PDFCreator([
            'paperSize' => 'A4',
        ]);

        $institusi = $this->dataInstitusiModel->getWithInstitusi($this->institusiId);
        $title = 'Surat Tugas';
        $data = $this->model->withPegawaiAndSurat()
            ->where('tb_surat_tugas.id', $this->letterId)
            ->first();

        $startDate = $data['tgl_berangkat'];
        $endDate = $data['tgl_kembali'];

        if ($startDate === $endDate) {
            $taskPeriod = osdate()->create($startDate);
        } else {
            $taskPeriod = osdate()->create($startDate) . ' sd. ' . osdate()->create($endDate);
        }

        $contentData = [
            'title'         => $title,
            'letterNumber'  => $data['surat_nomor_surat'],
            'schoolName'    => $institusi['nama_sekolah'],
            'headmaster'    => $institusi['kepala_sekolah'],
            'headmasterId'  => $institusi['nip_kepala_sekolah'],
            'employee'      => $data['pegawai_nama'],
            'employeeId'    => $data['pegawai_nip'],
            'position'      => $data['pegawai_jabatan'] . ' ' . $institusi['nama_sekolah'],
            'task'          => $data['tujuan'],
            'location'      => $data['lokasi'],
            'taskPeriod'    => $taskPeriod,
            'date'          => osdate()->create($data['surat_tgl_surat']),
            'marginLeft'    => '50%',
        ];

        $data = [
            'pageTitle' => $title,
            'content'   => view('surat-pegawai/surat_tugas', $contentData),
            'institusi' => $institusi
        ];

        $html = view('layout/main', $data);
        $pdf->loadHTML($html)->render()->stream('Surat-Tugas.pdf');
    }

    public function save()
    {
        $rules = [
            'id'                => ['rules' => 'permit_empty', 'label' => 'ID'],
            'pegawai_id'        => ['rules' => 'required', 'label' => lang('FieldLabels.pegawai.nama')],
            'nomor_surat'       => ['rules' => 'required', 'label' => lang('FieldLabels.suratKeluar.nomor_surat')],
            'tgl_surat'         => ['rules' => 'required|valid_date', 'label' => lang('FieldLabels.suratKeluar.tgl_surat')],
            'tingkat_biaya'     => ['rules' => 'required', 'label' => lang('FieldLabels.sppd.tingkat_biaya')],
            'tujuan'            => ['rules' => 'required', 'label' => lang('FieldLabels.sppd.tujuan')],
            'transportasi'      => ['rules' => 'required|in_list[pribadi,umum,kantor,lainnya]', 'label' => lang('FieldLabels.sppd.transportasi')],
            'lokasi'            => ['rules' => 'required', 'label' => lang('FieldLabels.sppd.lokasi')],
            'durasi'            => ['rules' => 'required|integer', 'label' => lang('FieldLabels.sppd.durasi')],
            'tgl_berangkat'     => ['rules' => 'required|valid_date', 'label' => lang('FieldLabels.sppd.tgl_berangkat')],
            'tgl_kembali'       => ['rules' => 'required|valid_date', 'label' => lang('FieldLabels.sppd.tgl_kembali')],
            'kepala_skpd'       => ['rules' => 'required', 'label' => lang('FieldLabels.sppd.kepala_skpd')],
            'nip_kepala_skpd'   => ['rules' => 'permit_empty|numeric|exact_length[18]', 'label' => lang('FieldLabels.sppd.nip_kepala_skpd')],
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
        $pegawaiId = $this->request->getPost('pegawai_id');

        $data = [
            'pegawai_id'        => $pegawaiId,
            'nomor_surat'       => $this->request->getPost('nomor_surat'),
            'tgl_surat'         => $this->request->getPost('tgl_surat'),
            'tingkat_biaya'     => $this->request->getPost('tingkat_biaya'),
            'tujuan'            => $this->request->getPost('tujuan'),
            'transportasi'      => $this->request->getPost('transportasi'),
            'lokasi'            => $this->request->getPost('lokasi'),
            'durasi'            => $this->request->getPost('durasi'),
            'tgl_berangkat'     => $this->request->getPost('tgl_berangkat'),
            'tgl_kembali'       => $this->request->getPost('tgl_kembali'),
            'kepala_skpd'       => $this->request->getPost('kepala_skpd'),
            'nip_kepala_skpd'   => $this->request->getPost('nip_kepala_skpd'),
        ];

        // simpan data surat keluar dulu
        $pegawaiDetail = $this->pegawaiModel->find($pegawaiId);

        $suratKeluarValues = [
            'institusi_id'  => get_institusi(),
            'nomor_surat'   => $data['nomor_surat'],
            'tujuan_surat'  => $pegawaiDetail['nama'],
            'perihal'       => 'Surat Perintah Tugas',
            'tgl_surat'     => $data['tgl_surat'],
            'keterangan'    => $data['tujuan'],
            'relasi_tabel'  => 'tb_surat_tugas',
        ];

        if ($suratId) {
            $suratKeluarValues['id'] = $suratId;
        }

        $this->suratKeluarModel->save($suratKeluarValues);

        try {
            $sppdValues = [
                'pegawai_id'        => $data['pegawai_id'],
                'surat_id'          => $suratId ?? $this->suratKeluarModel->getInsertID(),
                'tingkat_biaya'     => $data['tingkat_biaya'],
                'tujuan'            => $data['tujuan'],
                'transportasi'      => $data['transportasi'],
                'lokasi'            => $data['lokasi'],
                'durasi'            => $data['durasi'],
                'tgl_berangkat'     => $data['tgl_berangkat'],
                'tgl_kembali'       => $data['tgl_kembali'],
                'kepala_skpd'       => $data['kepala_skpd'],
                'nip_kepala_skpd'   => $data['nip_kepala_skpd'],
            ];

            $logMessage = 'membuat surat tugas untuk pegawai ' . $pegawaiDetail['nama'];

            if ($id) {
                $sppdValues['id'] = $id;
                $logMessage = str_replace('membuat', 'memperbarui', $logMessage);
            }

            $this->model->save($sppdValues);
            add_log($logMessage);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => lang('General.dataSaved'),
                'data'    => $sppdValues
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'data'    => $sppdValues
            ]);
        }
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
        $ids = array_map(fn($item) => decrypt($item, env('encryption_key')), $ids);

        // Validasi: pastikan semua ID tersedia
        $existing = $this->model->whereIn('id', $ids)->findAll();

        if (count($existing) !== count($ids)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => lang('General.dataNotFound')
            ]);
        }

        // ambil data surat keluar terkait
        $suratIds = array_map(fn($item) => $item['surat_id'], $existing);

        // hapus data SPPD
        $this->model->whereIn('id', $ids)->delete($ids, true);

        // hapus data surat keluar terkait
        $this->deleteSurat($suratIds, true);

        add_log('menghapus data SPPD sebanyak ' . count($ids) . ' baris');

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted')
        ]);
    }
    public function findEmployee()
    {
        $search = $this->request->getPost('search');
        $rows = 0;

        $data = [];
        if (strlen($search) > 2) {
            $builder = $this->pegawaiModel
                ->where('institusi_id', get_institusi())
                ->groupStart()
                ->like('nama', $search)
                ->orLike('nip', $search)
                ->groupEnd();

            $rows = $builder->countAllResults(false);
            $data = $builder->findAll();
        }

        return $this->response->setJSON([
            'status'        => 'OK',
            'message'       => lang('General.dataFetched'),
            'totalRows'     => $rows,
            'result'        => $data
        ]);
    }
}
