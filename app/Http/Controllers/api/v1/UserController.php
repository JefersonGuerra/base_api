<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function get(Request $request)
    {
        $modelUsers = new UserModel;
        return $modelUsers->get($request);
    }

    public function post(Request $request)
    {
        $modelUsers = new UserModel;
        if ($modelUsers::rules($request)) return $modelUsers::rules($request);
        if (!$modelUsers->post($request)) return response(null, 503);
        return response()->json(["success" => "Usuário criado com sucesso!"], 201);
    }

    public function put(Request $request)
    {
        $modelUsers = new UserModel;
        if (!$modelUsers->put($request)) return response(null, 503);
        return response()->json(["success" => "Usuário atualizado com sucesso!"], 201);
    }

    public function delete(Request $request)
    {
        $modelUsers = new UserModel;
        if (!$modelUsers->deleteUser($request)) return response(null, 503);
        return response()->json(["success" => "Usuário deletado com sucesso!"], 201);
    }
}
