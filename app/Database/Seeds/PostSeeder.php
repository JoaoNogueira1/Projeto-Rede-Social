<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'usuario_id'    => 1,
                'texto'         => 'Olá gente! Minha primeira postagem via Seed na Rede Social!',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'usuario_id'    => 1,
                'texto'         => 'Olá gente! Texto para verificar a funcionalidade do Query Builder!',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('postagens')->insertBatch($data);
    }
}
