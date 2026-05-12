<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaLikes extends Migration
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
        'postagem_id' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
        'usuario_id' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
    ]);

    $this->forge->addKey('id', true);
    
    // Chaves Estrangeiras
    $this->forge->addForeignKey('postagem_id', 'postagens', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
    
    $this->forge->createTable('likes');
}

public function down()
{
    $this->db->disableForeignKeyChecks();
    $this->forge->dropTable('likes');
    $this->db->enableForeignKeyChecks();
}
}
