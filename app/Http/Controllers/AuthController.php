<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    public function login(Request $request){
        $credenciais = $request->all(['email', 'password']);

        $token = auth('api')->attempt($credenciais);

        if($token){
            return response()->json(['token' => $token]);
        }else{
            return response()->json(['erro' => 'Usuário ou senha inválidos'], 403);
        }
    }

    public function logout(){
        auth('api')->logout();
        return response()->json(['msg' => 'Logout realizado.']);
    }

    public function refresh(){
        $newToken = auth('api')->refresh();
        return response()->json(['token' => $newToken]);
    }
}
