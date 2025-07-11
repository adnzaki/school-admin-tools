<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'nip'            => ['type' => 'VARCHAR', 'constraint' => 18, 'null' => true],
            'jabatan'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'jenis_pegawai'  => ['type' => 'ENUM', 'constraint' => ['PNS', 'PPPK', 'Honorer'], 'default' => 'PNS'],
            'email'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'telepon'        => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_pegawai');
    }

    public function down()
    {
        $this->forge->dropTable('tb_pegawai');
    }
}
