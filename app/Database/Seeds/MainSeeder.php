<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        //Chama primeiro o seeder do usuário
        $this->call('UsuarioSeeder');

        //Depois chama o seeder de postagens
        $this->call('PostSeeder');
    }
}
