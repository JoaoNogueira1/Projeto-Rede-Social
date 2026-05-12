<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaUsuario extends Migration
{
    public function up()
    {
        // Definindo os campos da tabela conforme o Documento de Projeto 
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true, // Garante que não existam emails duplicados 
            ],
            'senha' => [
                'type'       => 'VARCHAR',
                'constraint' => '255', // Espaço para senhas criptografadas 
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        // Define a Chave Primária (PK) 
        $this->forge->addKey('id', true);
        
        // Cria a tabela 'usuarios' 
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        // Reverte a operação: apaga a tabela se necessário
        $this->forge->dropTable('usuarios');
    }
}