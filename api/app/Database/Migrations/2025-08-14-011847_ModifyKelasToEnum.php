<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyKelasToEnum extends Migration
{
    public function up()
    {
        $this->db->query("
            ALTER TABLE `tb_pindah_sekolah`
            MODIFY COLUMN `kelas` ENUM('I','II','III','IV','V','VI','VII','VIII','IX') NOT NULL
        ");
    }

    public function down()
    {
        $this->db->query("
            ALTER TABLE `tb_pindah_sekolah`
            MODIFY COLUMN `kelas` VARCHAR(50) NOT NULL
        ");
    }
}
