<?php

namespace App\Http\Controllers;

use App\Models\Historico;
use App\Models\Produto;
use App\Models\Imagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isNull;

class ProdutoController extends Controller
{

    public function __construct(Produto $produto, Historico $historico, Imagem $imagem)
    {
        $this->middleware('jwt.auth');
        $this->imagem = $imagem;
        $this->produto = $produto;
        $this->historico = $historico;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->produto->with(['historicos', 'imagens'])->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $produto = $this->produto->create($request->all());

        $this->historico->create([
            'produto_id' => $produto->id,
            'tipo' => 'Cadastro'
        ]);

        return response()->json($produto, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produto = $this->produto()->find($id);
        if ($produto === null) {
            return response()->json('erro', 'Recurso solicitado não existe!');
        }
        return response()->json($produto->with('historicos'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $produto = $this->produto->find($id);
        if ($produto === null) {
            return response()->json(['erro' => 'Impossível realizar atualização. Recurso solicitado não existe.'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach ($produto->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($produto->rules());
        }

        $produto->fill($request->all());
        $produto->save();

        $this->historico->create(
            [
                'produto_id' => $produto->id,
                'tipo' => 'Update'
            ]
        );

        return response()->json($produto, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produto = $this->produto->find($id);

        if ($produto === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. Recurso solicitado não existe.']);
        }

        $this->imagem->where('produto_id', $id)->delete();
        $this->historico->where('produto_id', $id)->delete();

        Storage::disk('public')->delete($produto->imagem);

        $produto->delete();
        return response()->json(['msg' => 'O produto foi removido com sucesso.'], 200);
    }
}
