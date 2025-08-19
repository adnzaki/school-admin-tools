<div class="pt-5 pl-20 pr-20 font-11">
    <div class="line-1">
        <h3 class="text-center text-underline font-12"><?= strtoupper($title) ?></h3>
        <p class="text-center mt--10">No. <?= $letterNumber ?></p>
    </div>

    <p class="line-1p5 mt-10 pt-10">
        Yang bertanda tangan di bawah ini Kepala <?= $schoolName ?>
        Kecamatan <?= $district ?>
        <?= $city ?>
        Provinsi <?= $province ?> menerangkan bahwa:
    </p>
    <table class="ml-20 pl-10 line-2">
        <tr>
            <td width="100">Nama</td>
            <td>: <?= $mutation['siswa_nama'] ?></td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>: <?= $mutation['siswa_no_induk'] ?> / <?= $mutation['siswa_nisn'] ?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>: <?= $gender ?></td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>: <?= $grade ?></td>
        </tr>
    </table>

    <p class="line-1p15 mt-10 pt-10">
        Sesuai dengan surat permohonan pindah sekolah dari orang tua atau wali murid:
    </p>
    <table class="ml-20 pl-10 line-2">
        <tr>
            <td width="100">Nama</td>
            <td>: <?= $parentName ?></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>: <?= $parentJob ?></td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Alamat</td>
            <td>
                : <?= $mutation['siswa_alamat'] ?> Kel. <?= $mutation['siswa_kelurahan'] ?>
                <br /> <span style="margin-left: 8px;">Kec.</span> <?= $mutation['siswa_kecamatan'] ?> Kab/Kota <?= $mutation['siswa_kab_kota'] ?>
            </td>
        </tr>
    </table>
    <p class="line-1p5 mt-10 pt-10">
        Telah mengajukan permohonan pindah ke <strong><?= $mutation['sd_tujuan'] ?></strong>
        di Desa/Kelurahan <strong><?= $mutation['kelurahan'] ?></strong> Kecamatan <strong><?= $mutation['kecamatan'] ?></strong>
        Kab/Kota <strong><?= $mutation['kab_kota'] ?></strong> Provinsi <strong><?= $mutation['provinsi'] ?></strong>
        dengan alasan <strong><?= $mutation['alasan'] ?></strong>.<br /><br />
        Bersama ini kami sertakan buku laporan pendidikan (raport) yang bersangkutan
        dan surat permohonan pindah dari orang tua murid.
    </p>
</div>