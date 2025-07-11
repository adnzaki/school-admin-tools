<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'            => ['type' => 'VARCHAR', 'constraint' => 100],
            'tempat_lahir'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'tanggal_lahir'   => ['type' => 'DATE', 'null' => true],
            'no_induk'        => ['type' => 'VARCHAR', 'constraint' => 9],
            'nisn'            => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'jenis_kelamin'   => ['type' => 'ENUM', 'constraint' => ['L', 'P'], 'default' => 'L'],
            'nama_ayah'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'pekerjaan_ayah'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'nama_ibu'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'pekerjaan_ibu'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'alamat'          => ['type' => 'TEXT', 'null' => true],
            'rt'              => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'rw'              => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'kelurahan'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kecamatan'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kab_kota'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'provinsi'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_siswa');
    }

    public function down()
    {
        $this->forge->dropTable('tb_siswa');
    }
}
