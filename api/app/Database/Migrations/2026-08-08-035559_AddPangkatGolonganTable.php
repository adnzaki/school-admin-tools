<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPangkatGolonganTable extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'pangkat_golongan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'jenis_pegawai' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_pangkat_golongan');

        $employeeFields = [
            'pangkat_golongan_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'after' => 'institusi_id',
            ],
        ];
        $this->forge->addColumn('tb_pegawai', $employeeFields);
        $this->forge->addForeignKey('pangkat_golongan_id', 'tb_pangkat_golongan', 'id');
    }

    public function down()
    {
        //
    }
}
