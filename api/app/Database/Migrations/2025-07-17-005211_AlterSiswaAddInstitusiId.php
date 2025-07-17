<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterSiswaAddInstitusiId extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_siswa', [
            'institusi_id' => [
                'type'       => 'INT',
                'null'       => false,
                'after'      => 'id',
            ],
        ]);

        $this->db->query('ALTER TABLE tb_siswa ADD CONSTRAINT fk_siswa_institusi FOREIGN KEY (institusi_id) REFERENCES tb_institusi(id) ON DELETE CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('tb_siswa', 'fk_siswa_institusi');
        $this->forge->dropColumn('tb_siswa', 'institusi_id');
    }
}
