<?php
$model = new \App\Models\DataInstitusiModel();
// $institusiId = get_institusi();
$institusiId = 1;
$dataInstitusi = $model->getWithInstitusi(1);
$kopSekolah = base_url() . 'uploads/kop/' . $dataInstitusi['file_kop'];

?>

<?php if (! isset($useHeader)) : ?>
    <img src="<?= $kopSekolah ?>" class="kop" alt="Kop <?= $dataInstitusi['nama_sekolah'] ?>">
<?php endif; ?>