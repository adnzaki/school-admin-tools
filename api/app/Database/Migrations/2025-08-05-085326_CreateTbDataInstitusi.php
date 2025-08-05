<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbDataInstitusi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                       => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'institusi_id'             => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'kepala_sekolah'           => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nip_kepala_sekolah'       => [
                'type'       => 'VARCHAR',
                'constraint' => 18,
            ],
            'wakil_kepala_sekolah'     => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nip_wakil_kepala_sekolah' => [
                'type'       => 'VARCHAR',
                'constraint' => 18,
            ],
            'bendahara_bos'            => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nip_bendahara_bos'        => [
                'type'       => 'VARCHAR',
                'constraint' => 18,
            ],
            'bendahara_barang'         => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nip_bendahara_barang'     => [
                'type'       => 'VARCHAR',
                'constraint' => 18,
            ],
            'alamat'                   => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kelurahan'                => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kecamatan'                => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kab_kota'                 => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'provinsi'                 => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'file_kop'                 => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at'               => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'               => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at'               => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('institusi_id', 'tb_institusi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_data_institusi');
    }

    public function down()
    {
        $this->forge->dropTable('tb_data_institusi', true);
    }
}
