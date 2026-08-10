<div class="pt-5 pl-50 pr-50 font-10" style="margin-top: 20px; font-family: Arial, Helvetica, sans-serif;">
    <table class="line-1" style="border-collapse: collapse; width: 100%;">
        <tr>
            <td class="bordered pb-5 pl-5" style="width: 50%;"></td>
            <td class="bordered pb-5 pl-5" style="width: 50%;">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td style="width: 7%;">I.</td>
                        <td style="width: 30%;" class="pl-5">Berangkat dari</td>
                        <td style="width: 63%;">: <?= $schoolName ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" class="pl-5">(Tempat Kedudukan)</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="pl-5">Ke</td>
                        <td>: <?= $location ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="pl-5">Pada tanggal</td>
                        <td>: <?= $departureDate ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" class="pl-5">
                            Kepala <?= $schoolName ?><br><br><br><br><br>
                            <strong><?= $headmaster ?></strong><br>
                            NIP. <?= formatNIP($headmasterId) ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Baris 2 -->
        <tr>
            <td class="bordered pb-5 pl-5" style="width: 50%;">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td style="width: 7%;">II.</td>
                        <td style="width: 30%;" class="pl-5">Tiba di</td>
                        <td style="width: 63%;">: <?= $location ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="pl-5" class="pl-5">Pada tanggal</td>
                        <td>: <?= $departureDate ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" class="pl-5">
                            <br><?= $headOfSKPDPosition ?> <br><br><br><br><br>
                            <strong><?= $headOfSKPD ?></strong><br>
                            NIP. <?= formatNIP($headOfSKPDId) ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="bordered pb-5 pl-5" style="width: 50%;">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td style="width: 7%;"></td>
                        <td style="width: 30%;" class="pl-5">Berangkat dari</td>
                        <td style="width: 63%;">: <?= $location ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="pl-5">Ke</td>
                        <td>: <?= $schoolName ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="pl-5">Pada tanggal</td>
                        <td>: <?= $returnDate ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" class="pl-5">
                            <?= $headOfSKPDPosition ?> <br><br><br><br><br>
                            <strong><?= $headOfSKPD ?></strong><br>
                            NIP. <?= formatNIP($headOfSKPDId) ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Baris 3-5 -->
        <?php
        $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        ?>
        <?php for ($i = 3; $i <= 5; $i++) { ?>
            <tr>
                <td class="bordered pb-5 pl-5" style="width: 50%;">
                    <table style="border-collapse: collapse; width: 100%;">
                        <tr>
                            <td style="width: 7%;"><?= $romans[$i] ?>.</td>
                            <td style="width: 30%;" class="pl-5">Tiba di</td>
                            <td style="width: 63%;">: ..............................</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="pl-5" class="pl-5">Pada tanggal</td>
                            <td>: ..............................</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2" class="pl-5">
                                <br>Kepala <br><br><br>
                                <strong>......................................</strong><br>
                                NIP. ..............................
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="bordered pb-5 pl-5" style="width: 50%;">
                    <table style="border-collapse: collapse; width: 100%;">
                        <tr>
                            <td style="width: 7%;"></td>
                            <td style="width: 30%;" class="pl-5">Berangkat dari</td>
                            <td style="width: 63%;">: ..............................</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="pl-5" class="pl-5">Ke</td>
                            <td>: ..............................</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="pl-5" class="pl-5">Pada tanggal</td>
                            <td>: ..............................</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2" class="pl-5">
                                Kepala <br><br><br>
                                <strong>......................................</strong><br>
                                NIP. ..............................
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php } ?>
        <!-- Baris 6 -->
        <tr>
            <td class="bordered pl-5" style="width: 50%;">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td style="width: 7%;">VI.</td>
                        <td style="width: 30%;" class="pl-5">Tiba di</td>
                        <td style="width: 63%;">: <?= $schoolName ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="pl-5">Pada tanggal</td>
                        <td>: <?= $returnDate ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="pl-5">Tempat</td>
                        <td>: <?= $schoolAddress ?></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td colspan="2" class="pl-5">
                            <span style="margin-top: 10px; display: block;">
                                Pejabat yang memberi perintah
                            </span><br><br><br><br>
                            <strong><?= $headmaster ?></strong><br>
                            NIP. <?= formatNIP($headmasterId) ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="bordered pb-5 pt-5 pl-10 pr-10 text-justify" style="width: 50%;">
                Telah diperiksa dengan keterangan bahwa perjalanan
                tersebut atas perintah dan semata-mata untuk
                kepentingan jabatan dalam waktu yang sesingkat -
                singkatnya.
                <br><br><br><br><br><br><br><br><br><br>
            </td>
        </tr>

        <!-- Baris 7 -->
        <tr>
            <td class="bordered pl-5" colspan="2">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td style="width: 3%;">VII.</td>
                        <td colspan="2" class="pl-5">Catatan Lain-Lain</td>
                    </tr>

                </table>
            </td>
        </tr>
        <tr>
            <td class="bordered pl-5" colspan="2">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td style="width: 3%;">VIII.</td>
                        <td colspan="2">
                            PERHATIAN:
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" class="line-1p15 text-justify pr-10 pb-5">
                            Pengguna Anggaran/Kuasa Pengguna Anggaran yang menerbitkan SPD,
                            pejabat/pegawai/pihak lain yang melakukan perjalanan dinas, para pejabat yang
                            mengesahkan tanggal berangkat/tiba, serta bendahara pengeluaran bertanggung
                            jawab berdasarkan peraturan-peraturan Keuangan Daerah apabila negara
                            menderita rugi akibat kesalahan, kelalaian, dan kealpaannya.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</div>