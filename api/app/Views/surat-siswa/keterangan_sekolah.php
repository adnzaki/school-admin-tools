<div class="pt-5 pl-20 pr-20 font-11">
    <div class="line-1">
        <h3 class="text-center text-underline font-12"><?= strtoupper($title) ?></h3>
        <p class="text-center mt--10">No. <?= $letterNumber ?></p>
    </div>

    <p class="line-2 mt-10 pt-10">
        Yang bertanda tangan di bawah ini Kepala <?= $schoolName ?>
        Kecamatan <?= $district ?>
        <?= $city ?>
        Provinsi <?= $province ?> menerangkan bahwa:
    </p>
    <table class="ml-20 mt-10 pl-10 line-2">
        <tr>
            <td>Nama</td>
            <td>: <?= $letter['siswa_nama'] ?></td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>: <?= $letter['tempat_lahir'] ?>, <?= osdate()->create($letter['tgl_lahir']) ?></td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>: <?= $letter['siswa_no_induk'] ?> / <?= $letter['siswa_nisn'] ?></td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Alamat</td>
            <td>
                : <?= $letter['siswa_alamat'] ?> RT <?= add_zero_prefix($letter['siswa_rt']) ?> / <?= add_zero_prefix($letter['siswa_rw']) ?> Kel. <?= $letter['siswa_kelurahan'] ?>
                <br /> <span style="margin-left: 8px;">Kec.</span> <?= $letter['siswa_kecamatan'] ?> Kab/Kota <?= $letter['siswa_kab_kota'] ?>
            </td>
        </tr>

    </table>
    <p class="line-2 mt-10 pt-10">
        Nama tersebut benar merupakan siswa pada <?= $schoolName ?> Kecamatan <?= $district ?> <?= $city ?>
        Tahun Ajaran <?= $letter['tahun_ajaran'] ?> yang saat ini duduk di bangku kelas <?= $kelas ?>.
    </p>
    <p class="line-2 mt-10 pt-10">
        Demikian surat keterangan ini dibuat untuk dapat digunakan sebagaimana mestinya.
    </p>
</div>