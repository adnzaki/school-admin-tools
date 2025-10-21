<?php namespace App\Controllers;

use App\Models\SekolahDisiniModel;
use App\Models\SuratKeluarModel;
use App\Models\DataInstitusiModel;
use App\Models\SiswaModel;

class SekolahDisini extends BaseController
{
    use Traits\SuratTrait;
    use Traits\CommonTrait;

    private $model;

    private $letterId;

    private $suratKeluarModel;

    private $dataInstitusiModel;

    public function __construct()
    {
        $this->model = new SekolahDisiniModel();
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
            $container[$key]['kelas'] = $this->kelas[$value['kelas']];
            $container[$key]['tgl_surat'] = osdate()->create($value['tgl_surat'], 'd-M-y');
            $container[$key]['id'] = encrypt($value['id'], env('encryption_key'));
        }

        return $this->response->setJSON([
            'totalRows' => $totalRows,
            'container' => $container,
        ]);
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

        // hapus data keterangan siswa di sekolah ini
        $this->model->whereIn('id', $ids)->delete($ids, true);

        // hapus data surat keluar terkait
        $this->deleteSurat($suratIds, true);

        add_log('menghapus data keterangan siswa di sekolah ini sebanyak ' . count($ids) . ' baris dengan ID [ ' . implode(', ', $ids) . ' ]');

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataDeleted')
        ]);
    }

    public function getDetail(string $id)
    {
        $id = decrypt($id, env('encryption_key'));

        $data = $this->model->withSiswaAndSurat()
            ->where('tb_sekolah_disini.id', $id)
            ->first();

        return $this->response->setJSON($data);
    }

    public function save()
    {
        $rules = [
            'id'            => ['rules' => 'permit_empty', 'label' => 'ID'],
            'siswa_id'      => ['rules' => 'required', 'label' => lang('FieldLabels.siswa.nama')],
            'nomor_surat'   => ['rules' => 'required', 'label' => lang('FieldLabels.suratKeluar.nomor_surat')],
            'tgl_surat'     => ['rules' => 'required|valid_date', 'label' => lang('FieldLabels.suratKeluar.tgl_surat')],
            'kelas'         => ['rules' => 'required|in_list[1,2,3,4,5,6,7,8,9,10,11,12]', 'label' => lang('FieldLabels.mutasi.kelas')],
            'tahun_ajaran'  => ['rules' => 'required', 'label' => lang('FieldLabels.sekolahDisini.tahun_ajaran')],
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
            'kelas'         => $this->request->getPost('kelas'),
            'tahun_ajaran'  => $this->request->getPost('tahun_ajaran'),
        ];

        // simpan data surat keluar dulu
        $institusiDetail = $this->dataInstitusiModel->where('institusi_id', get_institusi())->first();
        $siswaModel = new SiswaModel();
        $siswaDetail = $siswaModel->find($siswaId);

        $suratKeluarValues = [
            'institusi_id'  => get_institusi(),
            'nomor_surat'   => $data['nomor_surat'],
            'tujuan_surat'  => 'Dinas Pendidikan <br/>' . $institusiDetail['kab_kota'],
            'perihal'       => 'Surat Ket. Siswa Di Sekolah Ini',
            'tgl_surat'     => $data['tgl_surat'],
            'keterangan'    => $siswaDetail['nama'] . ' (' . $siswaDetail['nisn'] . ')',
            'relasi_tabel'  => 'tb_sekolah_disini',
        ];

        if ($suratId) {
            $suratKeluarValues['id'] = $suratId;
        }

        $this->suratKeluarModel->save($suratKeluarValues);

        $suratKeteranganValues = [
            'siswa_id'      => $data['siswa_id'],
            'surat_id'      => $suratId ?? $this->suratKeluarModel->getInsertID(),
            'kelas'         => $data['kelas'],
            'tahun_ajaran'  => $data['tahun_ajaran'],
        ];

        $logMessage = 'membuat surat keterangan siswa di sekolah ini atas nama ' . $siswaDetail['nama'] . ' (' . $siswaDetail['nisn'] . ')';

        if ($id) {
            $suratKeteranganValues['id'] = $id;
            $logMessage = str_replace('membuat', 'memperbarui', $logMessage);
        }

        $this->model->save($suratKeteranganValues);
        add_log($logMessage);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('General.dataSaved')
        ]);
    }
}