<?php

namespace App\Http\Controllers;

use App\Models\Historico;
use App\Models\Imagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagemController extends Controller
{
    public function __construct(Imagem $imagem, Historico $historico)
    {
        $this->historico = $historico;
        $this->middleware('jwt.auth');
        $this->imagem = $imagem;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $arquivos = $request->file('imagem');

        foreach($arquivos as $key => $img){
            $image = $img;
            if($image->getClientOriginalExtension() != 'jpg'){
                return response()->json(['erro' => 'Extensão do arquivo é inválido.']);
            }
            if($this->imagem->where('produto_id', $id)->count() === 3){
                return response()->json(['erro' => 'Quantidade máxima de imagens por produto atingida.']);
            }

            $imagem_urn = $image->store('imagens/produtos', 'public');

            $this->imagem->create([
                'produto_id' => $id,
                'path' => $imagem_urn
            ]);

            $this->historico->create([
                'produto_id' => $id,
                'tipo' => 'Adicionou uma imagem'
            ]);
        }
        return response()->json(['success' => 'Imagens armazenadas com sucesso']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id  
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = $this->imagem->find($id);

        if($image === null){
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $this->historico->create([
            'produto_id' => $image->produto_id,
            'tipo' => 'Apagou uma imagem'
        ]);

        Storage::disk('public')->delete($image->path);

        $image->delete();

        return response()->json(['msg' => 'Imagem removida com sucesso!'], 200);
    }
}
