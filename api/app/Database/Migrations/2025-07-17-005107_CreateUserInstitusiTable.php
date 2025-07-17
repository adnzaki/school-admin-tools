<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserInstitusiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'unsigned' => true],
            'institusi_id'  => ['type' => 'INT'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('institusi_id', 'tb_institusi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_user_institusi');
    }

    public function down()
    {
        $this->forge->dropTable('tb_user_institusi');
    }
}
