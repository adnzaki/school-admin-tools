<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJabatanKepalaSKPDField extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_surat_tugas', [
            'jabatan_kepala_skpd' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'kepala_skpd'
            ],
        ]);
    }

    public function down()
    {
        //
    }
}
