<?php

namespace App\Http\Usuarios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Usuarios\UsuariosValidations;
use App\Http\Usuarios\useCases\getUsuario;

class UsuariosController extends Controller
{

    protected $usuarios_validations;

    public function __construct(UsuariosValidations $usuarios_validations)
    {
        $this->usuarios_validations = $usuarios_validations;
    }

    public function get(Request $request)
    {
        $data = getUsuario::get($request);

        return response()->json(["msg" => "Usuarios listadas", "data" => $data,  "status" => true]);
    }

    public function destroy(Request $request)
    {
        $this->usuarios_validations->destroyValidation($request);

        return response()->json(["msg" => "Se ha eliminado el usuario",  "status" => true]);
    }

}
