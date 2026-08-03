<div class="pt-5 pl-20 pr-20 font-11" style="margin-top: 80px;">
    <div class="line-1">
        <h3 class="text-center font-12"><?= strtoupper($title) ?></h3>
    </div>

    <p class="line-1p5 mt-10 pt-20">
        Yang bertanda tangan di bawah ini:
    </p>

    <table class="ml-20 pl-10 line-2">
        <tr>
            <td>Nama</td>
            <td>: <?= $parentName ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: <?= $mutation['siswa_alamat'] ?></td>
        </tr>
        <tr>
            <td>Orang Tua / Wali dari</td>
            <td>: <?= $mutation['siswa_nama'] ?></td>
        </tr>
        <tr>
            <td>Nomor Induk</td>
            <td>: <?= $mutation['siswa_no_induk'] ?? '' ?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>: <?= $gender ?></td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>: <?= $grade ?></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>: <?= $parentJob ?></td>
        </tr>

    </table>

    <p class="line-1p5 mt-10 pt-10">
        Mengajukan permohonan pindah belajar anak kami tersebut ke <strong><?= $mutation['sd_tujuan'] ?></strong>
        di Desa/Kelurahan <strong><?= $mutation['kelurahan'] ?></strong> Kecamatan <strong><?= $mutation['kecamatan'] ?></strong>
        Kab/Kota <strong><?= $mutation['kab_kota'] ?></strong> Provinsi <strong><?= $mutation['provinsi'] ?></strong>
        dengan alasan <strong><?= $mutation['alasan'] ?></strong>.<br /><br />

        Demikianlah agar menjadi maklum dan terima kasih.
    </p>

    <table style="width: 100%;">
        <tr>
            <td class="text-center" width="40%">
                <p class="line-1p5 mt-20 pt-10">
                    Mengetahui,<br />
                    Wali Kelas
                </p>
                <p style="margin-top: 55px;" class="font-10"></p>
                <p style="margin-top: 70px;">
                    <strong><?= $homeroomTeacherName ?></strong><br />
                    NIP. <?= formatNIP($homeroomTeacherNIP) ?>
                    <?php
                    if ($homeroomTeacherNIP === null || $homeroomTeacherNIP === '') {
                        $loop = 40;
                        for ($i = 0; $i < $loop; $i++) {
                            echo '&nbsp;';
                        }
                    }
                    ?>
                </p>
            </td>
            <td width="20%"></td>
            <td class="text-center" width="40%">
                <p class="line-1p5 mt-10 pt-10">
                    Bekasi, <?= $date ?><br />
                    Orang Tua / Wali Murid
                </p>
                <p style="margin-top: 55px;" class="font-10"><small>Materai 10.000</small></p>
                <p style="margin-top: 53px;">
                    <strong><?= $parentName ?></strong><br />
                </p>
            </td>
        </tr>
    </table>
</div>