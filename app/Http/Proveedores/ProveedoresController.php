<?php

namespace App\Http\Proveedores;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Proveedores\ProveedoresValidations;

use App\Http\Proveedores\useCases\getProveedor;
use App\Http\Proveedores\useCases\firstProveedor;

class ProveedoresController extends Controller
{

    protected $proveedores_validations;

    public function __construct(ProveedoresValidations $proveedores_validations)
    {
        $this->proveedores_validations = $proveedores_validations;
    }

    public function get(Request $request)
    {
        $data = getProveedor::get($request);

        return response()->json(["msg" => "Proveedores listados", "data" => $data,  "status" => true]);
    }

    public function first(Request $request)
    {
        $data = firstProveedor::first($request);

        return response()->json(["msg" => "Proveedor listado", "data" => $data,  "status" => true]);
    }

    public function create(Request $request)
    {
        $this->proveedores_validations->createValidation($request);

        return response()->json(["msg" => "Se ha guardado el proveedor",  "status" => true]);
    }

    public function update(Request $request)
    {
        $this->proveedores_validations->updateValidation($request);

        return response()->json(["msg" => "Se ha actualizado el proveedor",  "status" => true]);
    }

    public function destroy(Request $request)
    {
        $this->proveedores_validations->destroyValidation($request);

        return response()->json(["msg" => "Se ha eliminado el proveedor",  "status" => true]);
    }

}
