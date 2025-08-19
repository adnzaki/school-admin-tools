<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSuratKeluarRelationship extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_surat_keluar', [
            'relasi_tabel' => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true,
                'after'         => 'keterangan'
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
