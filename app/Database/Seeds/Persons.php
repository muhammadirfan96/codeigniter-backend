<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Persons extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'jimmy',
                'email' => 'jimmy@email.com',
                'birthday' => '2002-07-10',
                'photo' => base_url('/img/portofolio/xxx.jpg'),
                'created_at' => '2023-08-12',
                'updated_at' => '2023-08-12',
            ],
        ];

        $this->db->table('persons')->insertBatch($data);
    }
}
