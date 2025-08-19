<?php
$model = new \App\Models\DataInstitusiModel();
$institusiId = get_institusi();
$dataInstitusi = $model->getWithInstitusi($institusiId);
$kopSekolah = base_url() . 'uploads/kop/' . $dataInstitusi['file_kop'];

?>

<?php if (! isset($useHeader)) : ?>
    <img src="<?= $kopSekolah ?>" class="kop" alt="Kop <?= $dataInstitusi['nama_sekolah'] ?>">
<?php endif; ?>