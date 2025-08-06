<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTbDataInstitusiMakeWakilNullable extends Migration
{
    public function up()
    {
        $fields = [
            'wakil_kepala_sekolah' => [
                'name'       => 'wakil_kepala_sekolah',
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'nip_wakil_kepala_sekolah' => [
                'name'       => 'nip_wakil_kepala_sekolah',
                'type'       => 'VARCHAR',
                'constraint' => 18,
                'null'       => true,
            ],
        ];

        $this->forge->modifyColumn('tb_data_institusi', $fields);
    }

    public function down()
    {
        // Optional: revert jadi NOT NULL (kalau sebelumnya memang NOT NULL)
        $fields = [
            'wakil_kepala_sekolah' => [
                'name'       => 'wakil_kepala_sekolah',
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'nip_wakil_kepala_sekolah' => [
                'name'       => 'nip_wakil_kepala_sekolah',
                'type'       => 'VARCHAR',
                'constraint' => 18,
                'null'       => false,
            ],
        ];

        $this->forge->modifyColumn('tb_data_institusi', $fields);
    }
}
