<div class="pt-5 pl-50 pr-50 font-11">
    <div class="line-1">
        <h3 class="text-center text-underline font-12"><?= strtoupper($title) ?></h3>
        <p class="text-center mt--10">No. <?= $letterNumber ?></p>
    </div>

    <p class="line-2 mt-10 pt-10">
        Yang bertanda tangan di bawah ini:
    </p>
    <table class="ml-20 mt-10 pl-10 line-2">
        <tr>
            <td>Nama</td>
            <td>: <?= $headmaster ?></td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>: <?= $headmasterId ?></td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: Kepala Sekolah</td>
        </tr>

    </table>
    <p class="line-2 mt-10 pt-10">
        Dengan ini memberikan tugas kepada:
    </p>
    <table class="ml-20 mt-10 pl-10 line-2">
        <tr>
            <td>Nama</td>
            <td>: <?= $employee ?></td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>: <?= $employeeId ?></td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: <?= $position ?></td>
        </tr>

    </table>

    <p class="line-2 mt-10 pt-10 text-justify">
        Untuk mengikuti <?= $task ?> yang diselenggarakan di <?= $location ?> pada tanggal <?= $taskPeriod ?>.
    </p>
    <p class="line-2 mt-10 pt-10">
        Demikian surat tugas ini diterbitkan untuk dilaksanakan dengan penuh tanggung jawab.
    </p>
</div>