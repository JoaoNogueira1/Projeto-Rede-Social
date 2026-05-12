<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaPostagens extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'usuario_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'texto' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        
        // Esta linha abaixo é a que causa erro se a tabela 'usuarios' não existir
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('postagens');
    }

    public function down()
    {
        // Desativa a verificação de chaves estrangeiras para evitar erro ao apagar
        $this->db->disableForeignKeyChecks();
        $this->forge->dropTable('postagens');
        $this->db->enableForeignKeyChecks();
    }
}