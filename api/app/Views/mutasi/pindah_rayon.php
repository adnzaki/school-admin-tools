<div class="pt-20 mt-10 pl-30 pr-50 font-11">
    <table class="line-1p15">
        <tr>
            <td width="50">Nomor</td>
            <td>: <?= $letterNumber ?></td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>: <?= $title ?></td>
        </tr>
    </table>
    <p style="margin-top: 30px">
        Kepada Yth.<br />
        Kepala <?= $destination ?><br />
        di- <br />
        <span style="margin-left: 30px;"><?= $city ?></span>
    </p>

    <p class="line-1p5 text-justify">
        Sehubungan dengan siswa kami yang akan melanjutkan belajar keluar daerah <?= $city ?>,
        dimana salah satu syaratnya adalah melaporkan surat keterangan pindah sekolah yang dikeluarkan oleh Dinas Pendidikan setempat.
    </p>
    <p class="line-1p5 text-justify mt-10">
        Untuk itu kami mohon dibuatkan surat keterangan pindah belajar dari <?= $city ?>.
    </p>
    <p class="line-1p5 mt-10">Adapun siswa tersebut adalah :</p>
    <table class="ml-20 pl-10 line-2">
        <tr>
            <td>Nama</td>
            <td>: <?= $mutation['siswa_nama'] ?></td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>: <?= $mutation['siswa_tempat_lahir'] ?>, <?= osdate()->create($mutation['siswa_tgl_lahir']) ?></td>
        </tr>
        <tr>
            <td>NISN</td>
            <td>: <?= $mutation['siswa_nisn'] ?></td>
        </tr>
        <tr>
            <td>Orang Tua / Wali</td>
            <td>: <?= $parentName ?></td>
        </tr>
        <tr>
            <td>Asal Sekolah</td>
            <td>: <?= $schoolName ?></td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Sekolah Tujuan</td>
            <td>
                : <?= $mutation['sd_tujuan'] ?>, Kab/Kota <?= $mutation['kab_kota'] ?><br />
                <span style="margin-left: 8px;">Provinsi</span> <?= $mutation['provinsi'] ?>
            </td>
        </tr>
    </table>
    <p class="line-1p5 text-justify mt-30 mb-20">
        Demikian surat permohonan ini kami buat agar yang berkepentingan menjadi maklum.
    </p>
</div>