<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropSuratIdField extends Migration
{
    public function up()
    {
        $this->forge->dropForeignKey('tb_surat_tugas', 'tb_sppd_surat_id_foreign');
        $this->forge->dropColumn('tb_surat_tugas', 'surat_id');
    }

    public function down()
    {
        //
    }
}
