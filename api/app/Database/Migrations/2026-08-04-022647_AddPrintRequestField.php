<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrintRequestField extends Migration
{
    public function up()
    {
        $fields = [
            'print_request' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'after' => 'tgl_pindah',
                'default' => 0
            ]
        ];

        $this->forge->addColumn('tb_pindah_sekolah', $fields);
    }

    public function down()
    {
        //
    }
}
