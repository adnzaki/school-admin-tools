<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStudentPurposeInEnrollmentLetter extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_sekolah_disini', [
            'keperluan' => [
                'type'       => 'ENUM',
                'constraint' => ['biasa', 'pip', 'peringkat'],
                'default'    => 'biasa',
                'null'       => false,
                'comment'    => 'Keperluan pembuatan surat',
                'after'      => 'tahun_ajaran',
            ],
        ]);
    }

    public function down()
    {
        //
    }
}
