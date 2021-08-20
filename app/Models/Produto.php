<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    protected $fillable = ['codigo', 'categoria', 'nome', 'preco', 'composicao','data_cadastro', 'tamanho','quantidade_produto'];

    public function rules(){
        return [
            'codigo' => 'required|unique:produtos,codigo,'.$this->id,
            'categoria' => 'required|max:50',
            'nome' => 'required|min:3|max:50',
            'preco' => 'required|numeric',
            'composicao' => 'required',
            'data_cadastro' => 'required|date', 
            'tamanho' => 'required',
            'quantidade_produto' => 'required|integer|min:1'
        ];
    }

    public function historicos(){
        return $this->hasMany(Historico::class);
    }

    public function imagens(){
        return $this->hasMany(Imagem::class);
    }
}
