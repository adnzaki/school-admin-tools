<div class="pt-5 pl-20 pr-20 font-11">
    <div class="line-1">
        <h3 class="text-center font-12"><?= strtoupper($title) ?></h3>
    </div>
    <table class="line-1p5 text-bold mt-40">
        <tr>
            <td width="150">Nama Siswa</td>
            <td>: <?= $mutation['siswa_nama'] ?></td>
        </tr>
        <tr>
            <td>Nomor Induk / NISN</td>
            <td>: <?= $mutation['siswa_no_induk'] ?> / <?= $mutation['siswa_nisn'] ?></td>
        </tr>
    </table>
    <p class="mt-20 text-center">Diisi oleh sekolah yang ditinggalkan</p>
    <table class="line-1p5" style="border-collapse: collapse; width: 100%;">
        <tr>
            <td class="text-center bordered pb-5" colspan="4" style="background-color: black; color: white">KELUAR</td>
        </tr>
        <tr class="text-bold text-center">
            <td class="bordered" style="width: 18%;">Tanggal</td>
            <td class="bordered" style="width: 12%;">Kelas</td>
            <td class="bordered" style="width: 30%;">Sebab-sebab keluar atau atas permintaan (tertulis)</td>
            <td class="bordered" style="width: 40%;">Tanda Tangan Kepala Sekolah, Stempel Sekolah, dan Tanda Tangan Orang Tua/Wali </td>
        </tr>
        <tr class="text-center line-1">
            <td class="bordered"><?= $date ?></td>
            <td class="bordered"><?= $grade ?></td>
            <td class="bordered"><?= $mutation['alasan'] ?></td>
            <td class="bordered">
                <p style="margin-bottom: 60px;"><?= $city ?>, <?= $date ?> <br>Kepala Sekolah</p>
                <p><strong><?= $headmaster ?></strong> <br>NIP. <?= $headmasterNIP ?></p>

                <p class="mb-50">Orang Tua / Wali</p>
                <p><?= $parentName ?></p>
            </td>
        </tr>
        <?php for ($i = 0; $i < 2; $i++): ?>
            <tr class="text-center line-1p15">
                <td class="bordered"></td>
                <td class="bordered"></td>
                <td class="bordered"></td>
                <td class="bordered">
                    <p style="margin-bottom: 60px;">_____________, ________________ <br>Kepala Sekolah</p>
                    <p><?php for ($x = 0; $x < 50; $x++) echo '.'; ?> <br><span style="margin-left: -160px;">NIP. </span></p>

                    <p class="mb-50">Orang Tua / Wali</p>
                    <p><?php for ($y = 0; $y < 40; $y++) echo '.'; ?></p>
                </td>
            </tr>
        <?php endfor; ?>
    </table>
</div>

<?= view('mutasi/lembar_rapor_masuk') ?>