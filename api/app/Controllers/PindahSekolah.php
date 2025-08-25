<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PindahSekolahModel;
use App\Models\SiswaModel;
use App\Models\SuratKeluarModel;
use App\Models\DataInstitusiModel;

class PindahSekolah extends BaseController
{
    private $model;

    private $suratKeluarModel;

    private $siswaModel;

    private $dataInstitusiModel;

    private $institusiId;

    private $kelas = [
        1  => 'I (Satu)',
        2  => 'II (Dua)',
        3  => 'III (Tiga)',
        4  => 'IV (Empat)',
        5  => 'V (Lima)',
        6  => 'VI (Enam)',
        7  => 'VII (Tujuh)',
        8  => 'VIII (Delapan)',
        9  => 'IX (Sembilan)',
        10 => 'X (Sepuluh)',
        11 => 'XI (Sebelas)',
        12 => 'XII (Dua Belas)'
    ];

    private $jenisKelamin = [
        'L' => 'Laki-laki',
        'P' => 'Perempuan'
    ];

    private $mutationId;

    private $notfoundReason = '[ <i>Alasan: ID pengguna atau ID mutasi tidak valid.</i> ]';

    public function __construct()
    {
        $this->model = new PindahSekolahModel();
        $this->suratKeluarModel = new SuratKeluarModel();
        $this->siswaModel = new SiswaModel();
        $this->dataInstitusiModel = new DataInstitusiModel();

        // get institusi_id based on PDF creation mode
        helper('sakola');

        /** @var \CodeIgniter\HTTP\IncomingRequest */
        $request = service('request');

        $userId = decrypt($request->getGet('user'), env('encryption_key'));
        $this->institusiId = env('pdf_mode') === 'production' ? get_institusi($userId) : env('institusi_id');
        $this->mutationId = decrypt($request->getGet('id'), env('encryption_key'));
    }

    public function getData(?string $rawParams = null)
    {
        $params = explode('_', $rawParams ?? '');

        $kelas    = null;
        $tglStart = null;
        $tglEnd   = null;

        if (count($params) === 1 && preg_match('/^\d{4}-\d{2}-\d{2}$/', $params[0])) {
            // hanya tglStart
            $tglStart = $params[0];
        } elseif (count($params) === 2 && preg_match('/^\d{4}-\d{2}-\d{2}$/', $params[0]) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $params[1])) {
            // tglStart dan tglEnd
            $tglStart = $params[0];
            $tglEnd   = $params[1];
        } elseif (count($params) === 3) {
            // kelas + tglStart + tglEnd
            $kelas    = $params[0];
            $tglStart = $params[1];
            $tglEnd   = $params[2];
        } elseif (count($params) === 1) {
            // hanya kelas
            $kelas = $params[0];
        }

        $limit   = (int)$this->request->getPost('limit');
        $offset  = (int)$this->request->getPost('offset');
        // $searchBy currently not implemented
        $orderBy = $this->request->getPost('orderBy');
        $sort    = $this->request->getPost('sort');
        $search  = $this->request->getPost('search');

        $builder = $this->model
            ->withSiswa()
            ->applyFilters($search, $kelas, $tglStart, $tglEnd)
            ->orderBy($orderBy, $sort);

        $totalRows = $builder->countAllResults(false);
        $container = $builder->findAll($limit, $offset);

        foreach ($container as $key => $value) {
            $container[$key]['kelas'] = $this->kelas[$value['kelas']];
            $container[$key]['tgl_pindah'] = osdate()->create($value['tgl_pindah'], 'd-M-y');
            $container[$key]['id'] = encrypt($value['id'], env('encryption_key'));
        }

        $institusi = $this->dataInstitusiModel->getWithInstitusi(get_institusi());

        return $this->response->setJSON([
            'container'     => $container,
            'totalRows'     => $totalRows,
            'schoolLevel'   => $institusi['tingkat'],
            'additionalResponse' => [
                'status'        => 'OK',
                'message'       => lang('General.dataFetched'),
            ]
        ]);
    }

    public function getDetail(string $id)
    {
        $id = decrypt($id, env('encryption_key'));
        $detail = $this->model->findByIdWithSiswa($id);
        $getSuratPindah = $this->getSuratPindahByRelation($id)->findAll();
        if (count($getSuratPindah) > 1) {
            $detail['no_surat_rayon'] = $getSuratPindah[1]['nomor_surat'];
        }

        return $this->response->setJSON([
            'status'        => 'OK',
            'message'       => lang('General.dataFetched'),
            'detail'        => $detail
        ]);
    }

    public function findStudent()
    {
        $search = $this->request->getPost('search');

        $data = [];
        if (strlen($search) > 2) {
            $builder = $this->siswaModel->findActiveStudent()->like('nama', $search);
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

    public function createSuratPindahSekolah()
    {
        if ($this->institusiId === null || !$this->mutationId) {
            $message = 'Surat pindah sekolah tidak ditemukan. <br /> ' . $this->notfoundReason;
            return view('mutasi/surat_notfound', ['message' => $message]);
        }

        $pdf = new \PDFCreator([
            'paperSize' => 'F4',
        ]);

        $institusi = $this->dataInstitusiModel->getWithInstitusi($this->institusiId);
        $title = 'Surat Keterangan Pindah Sekolah';
        $letterDetail = $this->model->find($this->mutationId);
        $mutationData = $this->model->findByIdWithSiswa($this->mutationId);
        $parentName = $mutationData['siswa_nama_ayah'] === null || $mutationData['siswa_nama_ayah'] === '' ? $mutationData['siswa_nama_ibu'] : $mutationData['siswa_nama_ayah'];
        $parentJob = $mutationData['siswa_pekerjaan_ayah'] === null || $mutationData['siswa_pekerjaan_ayah'] === '' ? $mutationData['siswa_pekerjaan_ibu'] : $mutationData['siswa_pekerjaan_ayah'];

        $contentData = [
            'title'         => $title,
            'letterNumber'  => $letterDetail['no_surat'],
            'schoolName'    => $institusi['nama_sekolah'],
            'district'      => $institusi['kecamatan'],
            'city'          => $institusi['kab_kota'],
            'province'      => $institusi['provinsi'],
            'mutation'      => $mutationData,
            'grade'         => $this->kelas[$mutationData['kelas']],
            'gender'        => $this->jenisKelamin[$mutationData['siswa_jenis_kelamin']],
            'parentName'    => $parentName,
            'parentJob'     => $parentJob,
            'date'          => osdate()->create($mutationData['tgl_pindah']),
        ];

        $data = [
            'pageTitle' => 'Surat Keterangan Pindah Sekolah',
            'content'   => view('mutasi/pindah_sekolah', $contentData),
            'institusi' => $institusi
        ];

        $html = view('layout/main', $data);
        $pdf->loadHTML($html)->render()->stream('Surat-Pindah.pdf');
    }

    public function createSuratPindahRayon()
    {
        if ($this->institusiId === null || !$this->mutationId) {
            $message = 'Surat Pindah Rayon tidak ditemukan. <br/>' . $this->notfoundReason;
            return view('mutasi/surat_notfound', ['message' => $message]);
        }

        $mutationData = $this->model->findByIdWithSiswa($this->mutationId);

        if ((int)$mutationData['pindah_rayon'] !== 1) {
            $data = [
                'message' => '<p>Permohonan pindah rayon atas nama <span class="highlight">' . $mutationData['siswa_nama'] . '</span> tidak ditemukan.</p>'
            ];

            return view('mutasi/surat_notfound', $data);
        }

        $pdf = new \PDFCreator([
            'paperSize' => 'F4',
        ]);

        $institusi = $this->dataInstitusiModel->getWithInstitusi($this->institusiId);

        $title = 'Permohonan Pindah Rayon';
        $letterDetail = $this->getSuratPindahByRelation($this->mutationId, true)->findAll();


        $parentName = $mutationData['siswa_nama_ayah'] === null || $mutationData['siswa_nama_ayah'] === '' ? $mutationData['siswa_nama_ibu'] : $mutationData['siswa_nama_ayah'];

        $contentData = [
            'title'         => $title,
            'letterNumber'  => $letterDetail[1]['nomor_surat'], // Pindah Rayon must be in the second row
            'destination'   => $letterDetail[1]['tujuan_surat'],
            'city'          => $institusi['kab_kota'],
            'schoolName'    => $institusi['nama_sekolah'],
            'mutation'      => $mutationData,
            'parentName'    => $parentName,
            'date'          => osdate()->create($mutationData['tgl_pindah']),
        ];

        $data = [
            'pageTitle' => $title,
            'content'   => view('mutasi/pindah_rayon', $contentData),
            'institusi' => $institusi
        ];

        $html = view('layout/main', $data);
        $pdf->loadHTML($html)->render()->stream('Surat-Pindah-Rayon.pdf');
    }

    public function createLembarMutasiRapor()
    {
        if ($this->institusiId === null || !$this->mutationId) {
            $message = 'Lembar mutasi rapor tidak ditemukan. <br/>' . $this->notfoundReason;
            return view('mutasi/surat_notfound', ['message' => $message]);
        }

        $mutationData = $this->model->findByIdWithSiswa($this->mutationId);
        $pdf = new \PDFCreator([
            'paperSize' => 'A4',
        ]);

        $title = 'Keterangan Pindah Sekolah';
        $parentName = $mutationData['siswa_nama_ayah'] === null || $mutationData['siswa_nama_ayah'] === '' ? $mutationData['siswa_nama_ibu'] : $mutationData['siswa_nama_ayah'];
        $institusi = $this->dataInstitusiModel->getWithInstitusi($this->institusiId);

        $contentData = [
            'useHeader'     => false,
            'useSignature'  => false,
            'title'         => $title,
            'mutation'      => $mutationData,
            'parentName'    => $parentName,
            'grade'         => $this->kelas[$mutationData['kelas']],
            'date'          => osdate()->create($mutationData['tgl_pindah']),
            'city'          => $institusi['kab_kota'],
            'headmaster'    => $institusi['kepala_sekolah'],
            'headmasterNIP' => formatNIP($institusi['nip_kepala_sekolah']),
        ];

        $data = [
            'pageTitle' => $title,
            'content'   => view('mutasi/lembar_rapor', $contentData),
            'institusi' => $institusi
        ];

        $html = view('layout/main', $data);
        $pdf->loadHTML($html)->render()->stream('Lembar-Mutasi-Rapor.pdf');
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        $id = decrypt($id, env('encryption_key'));

        // get "Surat Keterangan Pindah Sekolah" and "Surat Permohonan Pindah Rayon"
        $suratPindah = $this->getSuratPindahByRelation($id)->findAll();

        // delete archived letter
        foreach ($suratPindah as $key) {
            $this->suratKeluarModel->delete($key['id'], true);
        }

        // get mutation with student data
        $detail = $this->model->findByIdWithSiswa($id);

        // delete mutation data
        $this->model->delete($id, true);

        // return student mutation status back to 0
        $this->siswaModel->update($detail['siswa_id'], ['mutasi' => 0]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted'),
        ]);
    }

    public function save()
    {
        $pindahRayon = (int)$this->request->getPost('pindah_rayon');
        $nomorSuratRayon = $this->request->getPost('no_surat_rayon');
        $rules = [
            'id'                => ['rules' => 'permit_empty', 'label' => 'ID'],
            'siswa_id'          => ['rules' => 'required|numeric', 'label' => lang('FieldLabels.mutasi.siswa_id')],
            'no_surat'          => ['rules' => 'required', 'label' => lang('FieldLabels.mutasi.no_surat')],
            'kelas'             => ['rules' => 'required|in_list[1,2,3,4,5,6,7,8,9,10,11,12]', 'label' => lang('FieldLabels.mutasi.kelas')],
            'sd_tujuan'         => ['rules' => 'required', 'label' => lang('FieldLabels.mutasi.sd_tujuan')],
            'kelurahan'         => ['rules' => 'required', 'label' => lang('FieldLabels.mutasi.kelurahan')],
            'kecamatan'         => ['rules' => 'required', 'label' => lang('FieldLabels.mutasi.kecamatan')],
            'kab_kota'          => ['rules' => 'required', 'label' => lang('FieldLabels.mutasi.kab_kota')],
            'provinsi'          => ['rules' => 'required', 'label' => lang('FieldLabels.mutasi.provinsi')],
            'alasan'            => ['rules' => 'required', 'label' => lang('FieldLabels.mutasi.alasan')],
            'tgl_pindah'        => ['rules' => 'required|valid_date', 'label' => lang('FieldLabels.mutasi.tgl_pindah')],
            'pindah_rayon'      => ['rules' => 'in_list[0,1]', 'label' => lang('FieldLabels.mutasi.pindah_rayon')],
            'no_surat_rayon'    => [
                'rules' => $pindahRayon === 1 ? 'required' : 'permit_empty',
                'label' => lang('FieldLabels.mutasi.no_surat_rayon')
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
        $siswaId = $this->request->getPost('siswa_id');

        $data = [
            'siswa_id'     => $siswaId,
            'no_surat'     => $this->request->getPost('no_surat'),
            'kelas'        => $this->request->getPost('kelas'),
            'sd_tujuan'    => $this->request->getPost('sd_tujuan'),
            'kelurahan'    => $this->request->getPost('kelurahan'),
            'kecamatan'    => $this->request->getPost('kecamatan'),
            'kab_kota'     => $this->request->getPost('kab_kota'),
            'provinsi'     => $this->request->getPost('provinsi'),
            'alasan'       => $this->request->getPost('alasan'),
            'tgl_pindah'   => $this->request->getPost('tgl_pindah'),
            'pindah_rayon' => $pindahRayon,
        ];

        if ($id) {
            $data['id'] = $id;
            $detail = $this->model->findByIdWithSiswa($id);
            if ($siswaId !== $detail['siswa_id']) {
                $this->siswaModel->update($detail['siswa_id'], ['mutasi' => 0]);
            }
        }

        $this->model->save($data);

        // Update status mutasi siswa
        $this->siswaModel->update($siswaId, ['mutasi' => 1]);

        // Buat surat keluar
        $siswaDetail = $this->siswaModel->find($siswaId);
        $pindahSekolahId = $id ?? $this->model->getInsertID();
        $relasiTabel = 'tb_pindah_sekolah.id=' . $pindahSekolahId;

        $dataSuratPindah = [
            'institusi_id' => get_institusi(),
            'nomor_surat'  => $data['no_surat'],
            'tujuan_surat' => $data['sd_tujuan'],
            'perihal'      => 'Keterangan Pindah Sekolah',
            'tgl_surat'    => $data['tgl_pindah'],
            'keterangan'   => $siswaDetail['nama'],
            'relasi_tabel' => $relasiTabel,
        ];

        $getSuratPindah = $id ? $this->getSuratPindahByRelation($pindahSekolahId)->findAll() : [];

        if ($id && count($getSuratPindah) > 0) {
            $dataSuratPindah['id'] = $getSuratPindah[0]['id'];
        }

        $this->suratKeluarModel->save($dataSuratPindah);

        // Buat surat keluar untuk pindah rayon
        if ($pindahRayon === 1) {
            $institusiDetail = $this->dataInstitusiModel->where('institusi_id', get_institusi())->first();

            $dataPindahRayon = [
                'institusi_id' => get_institusi(),
                'nomor_surat'  => $nomorSuratRayon,
                'tujuan_surat' => 'Dinas Pendidikan ' . $institusiDetail['kab_kota'],
                'perihal'      => 'Permohonan Pindah Rayon',
                'tgl_surat'    => $data['tgl_pindah'],
                'keterangan'   => $siswaDetail['nama'],
                'relasi_tabel' => $relasiTabel,
            ];

            // make sure that Surat Pindah Rayon is really exists
            if ($id && count($getSuratPindah) > 1) {
                $dataPindahRayon['id'] = $getSuratPindah[1]['id'];
            }

            $this->suratKeluarModel->save($dataPindahRayon);
        } else {
            if ($id && count($getSuratPindah) > 1) {
                $this->suratKeluarModel->delete($getSuratPindah[1]['id'], true);
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataSaved')
        ]);
    }

    private function getSuratPindahByRelation($id, $forPDF = false)
    {
        return $this->suratKeluarModel->where([
            'institusi_id' => $forPDF ? $this->institusiId : get_institusi(),
            'relasi_tabel' => 'tb_pindah_sekolah.id=' . $id
        ]);
    }
}
