<?php

namespace App\Http\Inventario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Inventario\InventarioValidations;
use App\Http\Inventario\useCases\getInventario;
use App\Http\Inventario\useCases\firstInventario;

class InventarioController extends Controller
{

    protected $inventario_validations;

    public function __construct(InventarioValidations $inventario_validations)
    {
        $this->inventario_validations = $inventario_validations;
    }

    public function get(Request $request)
    {
        $data = getInventario::get($request);

        return response()->json(["msg" => "Inventario listado", "data" => $data,  "status" => true]);
    }

    public function first(Request $request)
    {
        $data = firstInventario::first($request);

        return response()->json(["msg" => "Inventario listado", "data" => $data,  "status" => true]);
    }

    public function create(Request $request)
    {
        $this->inventario_validations->createValidation($request);

        return response()->json(["msg" => "Se ha guardado la mercancia",  "status" => true]);
    }

    public function update(Request $request)
    {
        $this->inventario_validations->updateValidation($request);

        return response()->json(["msg" => "Se ha actualizado la mercancia",  "status" => true]);
    }

    public function destroy(Request $request)
    {
        $this->inventario_validations->destroyValidation($request);

        return response()->json(["msg" => "Se ha eliminado la mercancia",  "status" => true]);
    }

}
