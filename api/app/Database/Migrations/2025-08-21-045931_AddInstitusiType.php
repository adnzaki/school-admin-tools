<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInstitusiType extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_data_institusi', [
            'tingkat' => [
                'type'  => 'ENUM',
                'constraint' => ['SD', 'SMP', 'SLTA'],
                'after' => 'institusi_id',
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
