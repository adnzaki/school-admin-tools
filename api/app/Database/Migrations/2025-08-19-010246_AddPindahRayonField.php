<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPindahRayonField extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_pindah_sekolah', [
            'pindah_rayon' => [
                'type' => 'TINYINT',
                'default' => 0,
                'after' => 'alasan'
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
