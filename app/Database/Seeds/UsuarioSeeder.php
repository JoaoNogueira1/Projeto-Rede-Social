<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nome'  => 'Vinícius Mendes',//Crie com o seu nome
            'email' => 'viniciusmendes@teste.com',//use um e-mail ficticio com o seu nome
            'senha' => password_hash('123456', PASSWORD_DEFAULT),
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        //insere o usuario
        $this->db->table('usuarios')->insert($data);
    }
}
