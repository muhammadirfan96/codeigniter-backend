<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Persons extends Migration
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
            "name" => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            "email" => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            "birthday" => [
                'type' => 'DATE',
            ],
            "photo" => [
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
        $this->forge->createTable('persons');
    }

    public function down()
    {
        //
    }
}
