<?php

$kopSekolah = base_url() . 'uploads/kop/' . $institusi['file_kop'];

?>

<?php if (! isset($useHeader)) : ?>
    <img src="<?= $kopSekolah ?>" class="kop" alt="Kop <?= $institusi['nama_sekolah'] ?>">
<?php endif; ?>