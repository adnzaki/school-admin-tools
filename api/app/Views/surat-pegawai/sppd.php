<div class="pt-5 pl-50 pr-50 font-11">

    <table class="ml-50 mt-10 pl-50 line-1p15" style="margin-left: 40%;">
        <tr>
            <td>Lampiran Ke</td>
            <td>: </td>
        </tr>
        <tr>
            <td>Kode No.</td>
            <td>: </td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td>: <?= $letterNumber ?></td>
        </tr>

    </table>
    <div class="line-1 mt-20">
        <h3 class="text-center font-12"><?= strtoupper($title) ?></h3>
    </div>
    <table class="line-1p5" style="border-collapse: collapse; width: 100%;">

        <!-- Baris 1 -->
        <tr>
            <td class="bordered text-center pb-5" style="width: 5%;">1</td>
            <td class="bordered pb-5 pl-5" style="width: 40%;">Pejabat yang memberi perintah</td>
            <td class="bordered pb-5 pl-5" style="width: 55%;" colspan="2"><?= $headmaster ?></td>
        </tr>

        <!-- Baris 2 -->
        <tr>
            <td class="bordered text-center pb-5" rowspan="2">2</td>
            <td class="bordered pb-5 pl-5" rowspan="2">Nama / NIP Pegawai yang melaksanakan Perjalanan Dinas</td>
            <td class="bordered-dotted-bottom bordered-right pb-5 pl-5" colspan="2"><?= $employee ?></td>
        </tr>
        <tr>
            <td class="bordered-bottom bordered-right pb-5 pl-5" colspan="2"><?= $employeeId ?></td>
        </tr>

        <!-- Baris 3 -->
        <tr class="line-1p5">
            <td class="bordered text-center pb-5">3</td>
            <td class="bordered pb-5">
                <ul style="list-style: lower-alpha; margin-left: -10px;">
                    <li>Pangkat dan Golongan</li>
                    <li>Jabatan / Instansi</li>
                    <li>Tingkat Biaya Perjalanan Dinas</li>
                </ul>
            </td>
            <td class="bordered pb-5" colspan="2">
                <ul style="list-style: none; margin-left: -35px;">
                    <li>&nbsp;</li>
                    <li><?= $position ?></li>
                    <li><?= $costLevel ?></li>
                </ul>
            </td>
        </tr>

        <!-- Baris 4 -->
        <tr>
            <td class="bordered text-center pb-5">4</td>
            <td class="bordered pb-5 pl-5">Maksud Perjalanan Dinas</td>
            <td class="bordered pb-5 pl-5" colspan="2"><?= $task ?></td>
        </tr>

        <!-- Baris 5 -->
        <tr>
            <td class="bordered text-center pb-5">5</td>
            <td class="bordered pb-5 pl-5">Alat angkutan yang dipergunakan</td>
            <td class="bordered pb-5 pl-5" colspan="2"><?= $transportation ?></td>
        </tr>

        <!-- Baris 6 -->
        <tr class="line-1p5">
            <td class="bordered text-center pb-5">6</td>
            <td class="bordered pb-5">
                <ul style="list-style: lower-alpha; margin-left: -10px;">
                    <li>Tempat Berangkat</li>
                    <li>Tempat Tujuan</li>
                </ul>
            </td>
            <td class="bordered pb-5" colspan="2">
                <ul style="list-style: none; margin-left: -35px;">
                    <li><?= $schoolName ?></li>
                    <li><?= $location ?></li>
                </ul>
            </td>
        </tr>
        <!-- Baris 7 -->
        <tr class="line-1p5">
            <td class="bordered text-center pb-5">7</td>
            <td class="bordered pb-5">
                <ul style="list-style: lower-alpha; margin-left: -10px;">
                    <li>Lamanya Perjalanan Dinas</li>
                    <li>Tanggal Berangkat</li>
                    <li>Tanggal Kembali</li>
                </ul>
            </td>
            <td class="bordered pb-5" colspan="2">
                <ul style="list-style: none; margin-left: -35px;">
                    <li><?= $duration ?> hari</li>
                    <li><?= $departureDate ?></li>
                    <li><?= $returnDate ?></li>
                </ul>
            </td>
        </tr>

        <!-- Baris 8 -->
        <tr>
            <td class="bordered text-center pb-5" rowspan="2">8</td>
            <td class="bordered pb-5 pl-5">Pengikut:</td>
            <td class="bordered pb-5 pl-5">Tanggal Lahir</td>
            <td class="bordered pb-5 pl-5">Keterangan</td>
        </tr>

        <tr class="line-1p5">
            <td class="bordered pb-5">
                <ul style="list-style: none; margin-left: -35px;">
                    <?php for ($i = 1; $i <= 3; $i++) {
                    ?>
                        <li><?= $i ?>.</li>
                    <?php } ?>
                </ul>
            </td>
            <td class="bordered pb-5"></td>
            <td class="bordered pb-5"></td>
        </tr>

        <!-- Baris 9 -->
        <tr>
            <td class="bordered text-center pb-5" rowspan="3">9</td>
            <td class="bordered-left pb-5 pl-5">Pembebanan Anggaran</td>
            <td class="pb-5 pl-5"></td>
            <td class="bordered-right pb-5 pl-5"></td>
        </tr>
        <tr>
            <td class="bordered pb-5 pl-5">Instansi</td>
            <td class="bordered pb-5 pl-5" colspan="2">Dinas Pendidikan</td>
        </tr>
        <tr>
            <td class="bordered pb-5 pl-5">Akun</td>
            <td class="bordered pb-5 pl-5" colspan="2"></td>
        </tr>
        <!-- Baris 10 -->
        <tr>
            <td class="bordered text-center pb-5">10</td>
            <td class="bordered pb-5 pl-5">Keterangan lain-lain</td>
            <td class="bordered pb-5 pl-5" colspan="2"></td>
        </tr>
    </table>
</div>