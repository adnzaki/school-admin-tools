<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInstitusiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'auto_increment' => true],
            'nama_sekolah'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'penanggung_jawab'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'no_kontak'         => ['type' => 'VARCHAR', 'constraint' => 30],
            'active'            => ['type' => 'BOOLEAN', 'default' => true],
            'masa_aktif'        => ['type' => 'INT', 'comment' => 'Dalam hari'],
            'tgl_registrasi'    => ['type' => 'DATE'],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_institusi');
    }

    public function down()
    {
        $this->forge->dropTable('tb_institusi');
    }
}
