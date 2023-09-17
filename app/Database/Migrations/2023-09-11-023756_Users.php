<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            "email" => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            "password" => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            "refresh_token" => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            "created_at" => [
                'type' => 'DATE',
            ],
            "updated_at" => [
                'type' => 'DATE',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        //
    }
}
