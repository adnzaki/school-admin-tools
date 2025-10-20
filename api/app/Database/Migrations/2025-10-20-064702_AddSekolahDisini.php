<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSekolahDisini extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'siswa_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'surat_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
            ],
            'kelas' => [
                'type'          => 'ENUM',
                'constraint'    => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
            ],
            'tahun_ajaran' => [
                'type'          => 'VARCHAR',
                'constraint'    => 10,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'deleted_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addForeignKey('siswa_id', 'tb_siswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('surat_id', 'tb_surat_keluar', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_sekolah_disini');
    }

    public function down()
    {
        //
    }
}
