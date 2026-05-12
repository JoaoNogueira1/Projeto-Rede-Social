<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Categoria extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
    protected $attributes =[
        'id' => null,
        'nome' => null,
        'descricao' => null,
        'criado_em' => null,
        'atualizado_em' => null,
    ];
//metodo para tornar as primeiras letras maiúsculas
    public function setNome(string $nome):self{
        $this->attributes['nome'] = ucfirst($nome);
        return $this;
    }
//metodo para resumir a descrição
    public function getDescricaoResumida(int $limite = 50): string{
        if(empty($this->attributes['descricao'])){
            return '';
        }
        return substr($this->attributes['descricao'], 0, $limite) . '...';
    }

//metodo para ajustar a data em padrao brasileiro
    public function getCriadoEmBrasil():string{
        if(empty($this->attributes['criado_em'])){
            return '';
        }
        $date = new \DateTime($this->attributes['criado_em']);
        return $date->format('d/m/Y H:i:s');
    }
}
