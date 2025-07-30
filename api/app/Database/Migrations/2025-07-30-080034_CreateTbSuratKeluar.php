<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbSuratKeluar extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'auto_increment' => true],
            'institusi_id'    => ['type' => 'INT'],
            'nomor_surat'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'tujuan_surat'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'perihal'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'tgl_surat'       => ['type' => 'DATE'],
            'keterangan'      => ['type' => 'TEXT', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('institusi_id', 'tb_institusi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_surat_keluar');
    }

    public function down()
    {
        $this->forge->dropTable('tb_surat_keluar');
    }
}
