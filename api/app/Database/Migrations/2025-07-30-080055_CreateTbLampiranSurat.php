<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbLampiranSurat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'jenis_surat'  => ['type' => 'ENUM', 'constraint' => ['masuk', 'keluar']],
            'surat_id'     => ['type' => 'INT'],
            'nama_file'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'path'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        // Tidak menggunakan foreign key langsung ke dua tabel berbeda, karena bergantung pada `jenis_surat`.
        $this->forge->createTable('tb_lampiran_surat');
    }

    public function down()
    {
        $this->forge->dropTable('tb_lampiran_surat');
    }
}
