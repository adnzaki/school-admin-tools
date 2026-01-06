<div class="pt-5 pl-50 pr-50 font-10" style="margin-top: 50px; font-family: Arial, Helvetica, sans-serif;">
    <table class="line-1p15" style="border-collapse: collapse; width: 100%;">
        <tr>
            <td class="bordered pb-5 pl-5" style="width: 50%;"></td>
            <td class="bordered pb-5 pl-5" style="width: 50%;">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td style="width: 7%;">I</td>
                        <td style="width: 30%;">Berangkat dari</td>
                        <td style="width: 63%;">: <?= $schoolName ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Tempat</td>
                        <td>: <?= $schoolAddress ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ke</td>
                        <td>: <?= $location ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Pada tanggal</td>
                        <td>: <?= $departureDate ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            Kepala <?= $schoolName ?><br><br><br><br>
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
                        <td style="width: 7%;">II</td>
                        <td style="width: 30%;">Tiba di</td>
                        <td style="width: 63%;">: <?= $location ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Pada tanggal</td>
                        <td>: <?= $departureDate ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            <br><?= $headOfSKPDPosition ?> <br><br><br><br>
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
                        <td style="width: 30%;">Berangkat dari</td>
                        <td style="width: 63%;">: <?= $location ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ke</td>
                        <td>: <?= $schoolName ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Pada tanggal</td>
                        <td>: <?= $returnDate ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            <?= $headOfSKPDPosition ?> <br><br><br><br>
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
                            <td style="width: 7%;"><?= $romans[$i] ?></td>
                            <td style="width: 30%;">Tiba di</td>
                            <td style="width: 63%;">: ..............................</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Pada tanggal</td>
                            <td>: ..............................</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">
                                <br>Kepala <br><br><br><br>
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
                            <td style="width: 30%;">Berangkat dari</td>
                            <td style="width: 63%;">: ..............................</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Ke</td>
                            <td>: ..............................</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Pada tanggal</td>
                            <td>: ..............................</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">
                                Kepala <br><br><br><br>
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
            <td class="bordered pb-5 pt-5 pl-5" style="width: 50%;">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td style="width: 7%;">VI</td>
                        <td style="width: 30%;">Tiba di</td>
                        <td style="width: 63%;">: <?= $schoolName ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Pada tanggal</td>
                        <td>: <?= $returnDate ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Tempat</td>
                        <td>: <?= $schoolAddress ?></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td colspan="2"><br><br>
                            <span style="margin-top: 10px; display: block;">
                                Pejabat yang memberi perintah
                            </span><br><br><br><br>
                            <strong><?= $headmaster ?></strong><br>
                            NIP. <?= formatNIP($headmasterId) ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="bordered pb-5 pt-5 pl-10 pr-10" style="width: 50%;">
                Telah diperiksa dengan keterangan bahwa perjalanan
                tersebut atas perintah dan semata-mata untuk
                kepentingan jabatan dalam waktu yang sesingkat -
                singkatnya.
                <br><br><br>Pejabat yang memberi perintah<br><br><br><br><br>
                <strong><?= $headmaster ?></strong><br>
                NIP. <?= formatNIP($headmasterId) ?>
            </td>
        </tr>
    </table>
</div>