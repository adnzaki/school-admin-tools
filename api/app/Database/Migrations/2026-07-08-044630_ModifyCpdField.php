<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyCpdField extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('tb_siswa', ['cpd' => 
            ['name' => 'lulus', 'type' => 'tinyint', 'constraint' => 1, 'default' => 0],
        ]);
    }

    public function down()
    {
        //
    }
}
