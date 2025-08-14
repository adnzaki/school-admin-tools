<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbPindahSekolah extends Migration
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
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'no_surat' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'kelas' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'sd_tujuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kelurahan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kecamatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kab_kota' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'provinsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'alasan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tgl_pindah' => [
                'type' => 'DATE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Primary key
        $this->forge->addKey('id', true);
        // Foreign key to tb_siswa.id
        $this->forge->addForeignKey('siswa_id', 'tb_siswa', 'id', 'CASCADE', 'CASCADE');
        // Create table
        $this->forge->createTable('tb_pindah_sekolah');
    }

    public function down()
    {
        // Drop table (will also drop FK)
        $this->forge->dropTable('tb_pindah_sekolah', true);
    }
}
