<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHomeroomTeacherForMutation extends Migration
{
    public function up()
    {
        $fields = [
            'wali_kelas' => [
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'kelas',
            ]
        ];

        $this->forge->addColumn('tb_pindah_sekolah', $fields);
        $this->forge->addForeignKey('wali_kelas', 'tb_pegawai', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        //
    }
}
