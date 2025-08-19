<div class="pt-5 pl-20 pr-20 font-11">
    <div class="line-1">
        <h3 class="text-center font-12"><?= strtoupper($title) ?></h3>
    </div>
    <table class="line-1p5 text-bold mt-40">
        <tr>
            <td width="100">Nama Siswa</td>
            <td>: <?= $mutation['siswa_nama'] ?></td>
        </tr>
    </table>
    <p class="mt-20 text-center">Diisi oleh sekolah yang baru</p>
    <table class="line-2" style="border-collapse: collapse; width: 100%;">
        <tr>
            <td class="text-center bordered pb-5 line-1p5" colspan="4" style="background-color: black; color: white">MASUK</td>
        </tr>
        <tr class="text-center">
            <td class="text-bold bordered" style="width: 10%;">No.</td>
            <td class="text-bold bordered" style="width: 50%;" colspan="2">Identitas Peserta Didik</td>
            <td class="text-bold bordered" style="width: 40%;">Tanda Tangan Kepala Sekolah, Stempel Sekolah</td>
        </tr>
        <?php for ($j = 1; $j < 4; $j++): ?>
            <tr>
                <td class="text-center bordered" style="vertical-align: top; padding-top: 15px"><?= $j ?></td>
                <td class="bordered pl-5" style="width: 30%;">
                    <p>
                        Nama Peserta Didik <br>
                        Nomor Induk <br>
                        Nama Sekolah <br>
                        Masuk di sekolah ini <br>
                        a. Tanggal <br>
                        b. Kelas <br>
                        Tahun Pelajaran
                    </p>
                </td>
                <td class="bordered pl-5" style="width: 40%;">
                    <?php for ($k = 0; $k < 50; $k++) echo '.'; ?> <br>
                    <?php for ($k = 0; $k < 50; $k++) echo '.'; ?> <br>
                    <?php for ($k = 0; $k < 50; $k++) echo '.'; ?> <br><br>
                    <?php for ($k = 0; $k < 50; $k++) echo '.'; ?> <br>
                    <?php for ($k = 0; $k < 50; $k++) echo '.'; ?> <br>
                    <?php for ($k = 0; $k < 50; $k++) echo '.'; ?> <br>
                </td>
                <td class="text-center bordered line-1p15">
                    <p style="margin-bottom: 80px;">_______, ________ <br>Kepala Sekolah</p>
                    <p><?php for ($x = 0; $x < 50; $x++) echo '.'; ?> <br><span style="margin-left: -160px;">NIP. </span></p>
                </td>
            </tr>
        <?php endfor; ?>
    </table>
</div>