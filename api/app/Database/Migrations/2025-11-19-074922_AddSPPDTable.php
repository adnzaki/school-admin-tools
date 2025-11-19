<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSPPDTable extends Migration
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
            'pegawai_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'surat_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tingkat_biaya' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'tujuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'transportasi' => [
                'type'      => 'ENUM',
                'constraint' => ['pribadi', 'umum', 'kantor', 'lainnya'],
            ],
            'lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'durasi' => [
                'type'      => 'INT',
                'constraint' => 11,
                'comment'    => 'Dalam satuan hari',
            ],
            'tgl_berangkat' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tgl_kembali' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'kepala_skpd' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'nip_kepala_skpd' => [
                'type'       => 'VARCHAR',
                'constraint' => 18,
                'null'       => true,
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

        $this->forge->addKey('id', true, true);
        $this->forge->addForeignKey('pegawai_id', 'tb_pegawai', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('surat_id', 'tb_surat_keluar', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_sppd');


    }

    public function down()
    {
        //
    }
}
