<?php

namespace App\Controllers;

use App\Models\SuratTugasModel;
use App\Models\SuratKeluarModel;
use App\Models\DataInstitusiModel;
use App\Models\PegawaiModel;

class SuratTugas extends BaseController
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

        $builder = $this->model->withPegawai()
            ->search($keyword)
            ->orderBy($orderBy, $sort)
            ->orderBy('id', 'desc');

        $totalRows = $builder->countAllResults(false);
        $container = $builder->findAll($limit, $offset);

        foreach ($container as $key => $value) {
            $suratTugas = $this->getSuratTugasByRelation($container[$key]['id'])->first();
            $container[$key]['no_surat'] = $suratTugas['nomor_surat'] ?? '';
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

        $data = $this->model->withPegawai()
            ->where('tb_surat_tugas.id', $id)
            ->first();

        $suratTugas = $this->getSuratTugasByRelation($id)->findAll();
        $data['tgl_surat'] = $suratTugas[0]['tgl_surat'];
        $data['no_surat'] = $suratTugas[0]['nomor_surat'];
        if (count($suratTugas) > 1) {
            $data['no_sppd'] = $suratTugas[1]['nomor_surat'];
        }

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
        $data = $this->model->findByIdWithPegawai($this->letterId, $this->institusiId);

        $startDate = $data['tgl_berangkat'];
        $endDate = $data['tgl_kembali'];

        if ($startDate === $endDate) {
            $taskPeriod = osdate()->create($startDate);
        } else {
            $taskPeriod = osdate()->create($startDate) . ' sd. ' . osdate()->create($endDate);
        }

        $letterDetail = $this->getSuratTugasByRelation($this->letterId, true)->first();

        $contentData = [
            'title'         => $title,
            'letterNumber'  => $letterDetail['nomor_surat'],
            'schoolName'    => $institusi['nama_sekolah'],
            'headmaster'    => $institusi['kepala_sekolah'],
            'headmasterId'  => $institusi['nip_kepala_sekolah'],
            'employee'      => $data['pegawai_nama'],
            'employeeId'    => $data['pegawai_nip'],
            'position'      => $data['pegawai_jabatan'] . ' ' . $institusi['nama_sekolah'],
            'task'          => $data['tujuan'],
            'location'      => $data['lokasi'],
            'taskPeriod'    => $taskPeriod,
            'date'          => osdate()->create($letterDetail['tgl_surat']),
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

    public function createSppd()
    {
        if ($this->institusiId === null || !$this->letterId) {
            $message = 'Surat perjalanan dinas tidak ditemukan. <br/>' . $this->notfoundReason;
            return view('surat_notfound', ['message' => $message]);
        }

        // Tidak terdapat Surat Perjalanan Dinas untuk surat tugas ini

        $data = $this->model->findByIdWithPegawai($this->letterId, $this->institusiId);
        if ((int)$data['sppd'] === 0) {
            $message = [
                'message' => '<p>Tidak terdapat Surat Perjalanan Dinas untuk surat tugas  <span class="highlight">' . $data['tujuan'] . '</span>.</p>'
            ];

            return view('surat_notfound', $message);
        }

        $pdf = new \PDFCreator([
            'paperSize' => 'F4',
        ]);

        $institusi = $this->dataInstitusiModel->getWithInstitusi($this->institusiId);
        $title = 'Surat Perjalanan Dinas';

        $letterDetail = $this->getSuratTugasByRelation($this->letterId, true)->first();

        $startDate = $data['tgl_berangkat'];
        $endDate = $data['tgl_kembali'];

        if ($startDate === $endDate) {
            $taskPeriod = osdate()->create($startDate);
        } else {
            $taskPeriod = osdate()->create($startDate) . ' sd. ' . osdate()->create($endDate);
        }

        $transportType = [
            'pribadi' => 'Kendaraan Pribadi',
            'umum' => 'Transportasi Umum',
            'kantor' => 'Inventaris Kantor',
            'lainnya' => 'Lainnya'
        ];

        $contentData = [
            'title'                 => $title,
            'letterNumber'          => $letterDetail['nomor_surat'],
            'schoolName'            => $institusi['nama_sekolah'],
            'headmaster'            => $institusi['kepala_sekolah'],
            'headmasterId'          => $institusi['nip_kepala_sekolah'],
            'schoolAddress'         => $institusi['alamat'],
            'employee'              => $data['pegawai_nama'],
            'employeeId'            => $data['pegawai_nip'],
            'position'              => $data['pegawai_jabatan'] . ' / ' . $institusi['nama_sekolah'],
            'task'                  => $data['tujuan'],
            'location'              => $data['lokasi'],
            'costLevel'             => $data['tingkat_biaya'],
            'transportation'        => $transportType[$data['transportasi']],
            'taskPeriod'            => $taskPeriod,
            'duration'              => $data['durasi'],
            'departureDate'         => osdate()->create($data['tgl_berangkat']),
            'returnDate'            => osdate()->create($data['tgl_kembali']),
            'date'                  => osdate()->create($letterDetail['tgl_surat']),
            'marginLeft'            => '50%',
            'headOfSKPD'            => $data['kepala_skpd'],
            'headOfSKPDPosition'    => $data['jabatan_kepala_skpd'],
            'headOfSKPDId'          => $data['nip_kepala_skpd'],
        ];

        $data = [
            'pageTitle' => $title,
            'content'   => view('surat-pegawai/sppd', $contentData),
            'institusi' => $institusi
        ];

        $page1 = view('layout/main', $data);

        $html = $page1 . view('surat-pegawai/sppd-2');
        $pdf->loadHTML($html)->render()->stream('Surat Perjalanan Dinas.pdf');
    }

    public function save()
    {
        try {
            $sppd = (int)$this->request->getPost('sppd');
            $noSppd = $this->request->getPost('no_sppd');
            $requiredWithSPPD = $sppd === 1 ? 'required' : 'permit_empty';
            $rules = [
                'id'                => ['rules' => 'permit_empty', 'label' => 'ID'],
                'pegawai_id'        => ['rules' => 'required', 'label' => lang('FieldLabels.pegawai.nama')],
                'nomor_surat'       => ['rules' => 'required', 'label' => lang('FieldLabels.suratKeluar.nomor_surat')],
                'tgl_surat'         => ['rules' => 'required|valid_date', 'label' => lang('FieldLabels.suratKeluar.tgl_surat')],
                'tujuan'            => ['rules' => 'required', 'label' => lang('FieldLabels.sppd.tujuan')],
                'lokasi'            => ['rules' => 'required', 'label' => lang('FieldLabels.sppd.lokasi')],
                'durasi'            => ['rules' => 'required|integer', 'label' => lang('FieldLabels.sppd.durasi')],
                'tgl_berangkat'     => ['rules' => 'required|valid_date', 'label' => lang('FieldLabels.sppd.tgl_berangkat')],
                'tgl_kembali'       => ['rules' => 'required|valid_date', 'label' => lang('FieldLabels.sppd.tgl_kembali')],
                'no_sppd'           => [
                    'rules' => $requiredWithSPPD,
                    'label' => lang('FieldLabels.sppd.no_sppd')
                ],
                'kepala_skpd' => [
                    'rules' => $requiredWithSPPD,
                    'label' => lang('FieldLabels.sppd.kepala_skpd')
                ],
                'jabatan_kepala_skpd' => [
                    'rules' => $requiredWithSPPD,
                    'label' => lang('FieldLabels.sppd.jabatan_kepala_skpd')
                ],
                'nip_kepala_skpd'   => ['rules' => 'permit_empty|numeric|exact_length[18]', 'label' => lang('FieldLabels.sppd.nip_kepala_skpd')],
                'tingkat_biaya'     => ['rules' => $requiredWithSPPD, 'label' => lang('FieldLabels.sppd.tingkat_biaya')],
                'transportasi'      => ['rules' => $requiredWithSPPD . '|in_list[pribadi,umum,kantor,lainnya]', 'label' => lang('FieldLabels.sppd.transportasi')],
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
            $pegawaiId = $this->request->getPost('pegawai_id');

            $data = [
                'pegawai_id'            => $pegawaiId,
                'nomor_surat'           => $this->request->getPost('nomor_surat'),
                'tgl_surat'             => $this->request->getPost('tgl_surat'),
                'tingkat_biaya'         => $this->request->getPost('tingkat_biaya'),
                'tujuan'                => $this->request->getPost('tujuan'),
                'transportasi'          => $this->request->getPost('transportasi'),
                'lokasi'                => $this->request->getPost('lokasi'),
                'durasi'                => $this->request->getPost('durasi'),
                'tgl_berangkat'         => $this->request->getPost('tgl_berangkat'),
                'tgl_kembali'           => $this->request->getPost('tgl_kembali'),
                'sppd'                  => $sppd,
                'kepala_skpd'           => $this->request->getPost('kepala_skpd'),
                'jabatan_kepala_skpd'   => $this->request->getPost('jabatan_kepala_skpd'),
                'nip_kepala_skpd'       => $this->request->getPost('nip_kepala_skpd'),
            ];

            $logMessage = 'membuat data surat tugas untuk pegawai ' . $this->pegawaiModel->find($pegawaiId)['nama'];

            if ($id) {
                $data['id'] = $id;
                $logMessage = str_replace('membuat', 'memperbarui', $logMessage);
                $detail = $this->model->findByIdWithPegawai($id);
                if ($pegawaiId !== $detail['pegawai_id']) {
                    $logMessage = 'mengganti data surat tugas dari ' . $detail['pegawai_nama'] . ' menjadi ' . $this->pegawaiModel->find($pegawaiId)['nama'];
                }
            }

            $this->model->save($data);
            add_log($logMessage);

            // simpan data surat keluar dulu
            $pegawaiDetail = $this->pegawaiModel->find($pegawaiId);
            $suratTugasId = $id ?? $this->model->getInsertID();
            $relasiTabel = 'tb_surat_tugas.id=' . $suratTugasId;

            $suratKeluarValues = [
                'institusi_id'  => get_institusi(),
                'nomor_surat'   => $data['nomor_surat'],
                'tujuan_surat'  => $pegawaiDetail['nama'],
                'perihal'       => 'Surat Perintah Tugas',
                'tgl_surat'     => $data['tgl_surat'],
                'keterangan'    => $data['tujuan'],
                'relasi_tabel'  => $relasiTabel,
            ];

            $getSuratTugas = $id ? $this->getSuratTugasByRelation($suratTugasId)->findAll() : [];

            if ($id && count($getSuratTugas) > 0) {
                $suratKeluarValues['id'] = $getSuratTugas[0]['id'];
            }

            $this->suratKeluarModel->save($suratKeluarValues);

            // Buat surat keluar untuk sppd
            if ($sppd === 1) {

                $dataSppd = [
                    'institusi_id' => get_institusi(),
                    'nomor_surat'  => $noSppd,
                    'tujuan_surat' => $data['lokasi'],
                    'perihal'      => 'Surat Perjalanan Dinas',
                    'tgl_surat'    => $data['tgl_surat'],
                    'keterangan'   => $pegawaiDetail['nama'],
                    'relasi_tabel' => $relasiTabel,
                ];

                // make sure that SPPD is really exists
                if ($id && count($getSuratTugas) > 1) {
                    $dataSppd['id'] = $getSuratTugas[1]['id'];
                }

                $this->suratKeluarModel->save($dataSppd);
            } else {
                if ($id && count($getSuratTugas) > 1) {
                    $this->suratKeluarModel->delete($getSuratTugas[1]['id'], true);
                }
            }

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => lang('General.dataSaved')
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => $e->getMessage(),
                'error'     => $e->getTrace()
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

        $suratTugas = [];
        foreach ($existing as $item) {
            $suratTugas[] = $this->getSuratTugasByRelation($item['id'])->findAll();
        }

        // delete archived letter
        foreach ($suratTugas as $item) {
            $this->deleteSurat(array_map(fn($item) => $item['id'], $item), true);
        }

        // hapus data SPPD
        $this->model->whereIn('id', $ids)->delete($ids, true);

        add_log('menghapus data SPPD sebanyak ' . count($ids) . ' baris');

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted'),
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

    private function getSuratTugasByRelation($id, $forPDF = false)
    {
        return $this->suratKeluarModel->where([
            'institusi_id' => $forPDF ? $this->institusiId : get_institusi(),
            'relasi_tabel' => 'tb_surat_tugas.id=' . $id
        ]);
    }
}
