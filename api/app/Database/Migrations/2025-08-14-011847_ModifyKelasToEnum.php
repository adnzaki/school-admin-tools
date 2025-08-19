<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyKelasToEnum extends Migration
{
    public function up()
    {
        $this->db->query("
            ALTER TABLE `tb_pindah_sekolah`
            MODIFY COLUMN `kelas` ENUM('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12') NOT NULL
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
