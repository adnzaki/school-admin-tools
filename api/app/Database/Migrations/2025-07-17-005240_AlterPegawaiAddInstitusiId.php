<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterPegawaiAddInstitusiId extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_pegawai', [
            'institusi_id' => [
                'type'       => 'INT',
                'null'       => false,
                'after'      => 'id', // letakkan setelah ID
            ],
        ]);

        $this->db->query('ALTER TABLE tb_pegawai ADD CONSTRAINT fk_pegawai_institusi FOREIGN KEY (institusi_id) REFERENCES tb_institusi(id) ON DELETE CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('tb_pegawai', 'fk_pegawai_institusi');
        $this->forge->dropColumn('tb_pegawai', 'institusi_id');
    }
}
