<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Categoria;

class CategoriaModel extends Model
{
    protected $table            = 'categorias';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Categoria::class; // Dizemos que queremos Entity, não array
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome', 'descricao'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';

    // Validation
    protected $validationRules = [
    'id'        => 'permit_empty|is_natural_no_zero', // Adicione esta linha!
    
    'nome' => 'required|min_length[3]|max_length[100]|is_unique[categorias.nome,id,{id}]',
    
    'descricao' => 'permit_empty|max_length[500]'
];

    protected $validationMessages = [
        'nome' => [
            'required' => 'O nome da categoria é obrigatório',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres',
            'is_unique' => 'Já existe uma categoria com este nome'
        ]
    ];

    protected $skipValidation = false;
}