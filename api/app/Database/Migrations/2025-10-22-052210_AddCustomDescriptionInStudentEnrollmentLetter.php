<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomDescriptionInStudentEnrollmentLetter extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_sekolah_disini', [
            'deskripsi' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Keterangan custom untuk surat keterangan siswa di sekolah ini',
                'after'      => 'keperluan',
            ],
        ]);
    }

    public function down()
    {
        //
    }
}
