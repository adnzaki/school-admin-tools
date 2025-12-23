<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIncludeSPPDField extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_surat_tugas', [
            'sppd' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'after' => 'tgl_kembali',
                'default' => 0,
            ],
        ]);
    }

    public function down()
    {
        //
    }
}
