<?php
$headmaster = $institusi['kepala_sekolah'];
$headmasterNip = $institusi['nip_kepala_sekolah'];

?>
<?php if (! isset($useSignature)) : ?>
    <div style="margin-left: <?= $marginLeft ?? '60%' ?>;" class="<?= $signFontSize ?? 'font-11' ?>">
        <p>
            Bekasi, <?= $date ?><br />
            Kepala Sekolah
        </p>
        <p style="margin-top: 80px;">
            <strong><?= $headmaster ?></strong><br />
            NIP. <?= formatNIP($headmasterNip) ?>
        </p>
    </div>
<?php endif; ?>