<?php

namespace App\Http\VentasMercancias;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\VentasMercancias\useCases\getVentaMercancia;
use App\Http\VentasMercancias\useCases\firstVentaMercancia;
use App\Http\VentasMercancias\VentasMercanciasValidations;

class VentasMercanciasController extends Controller
{

    protected $ventas_mercancias_validations;

    public function __construct(VentasMercanciasValidations $ventas_mercancias_validations)
    {
        $this->ventas_mercancias_validations = $ventas_mercancias_validations;
    }

    public function get(Request $request)
    {
        $data = getVentaMercancia::get($request);

        return response()->json(["msg" => "Mercancias listadas", "data" => $data,  "status" => true]);
    }

    public function first(Request $request)
    {
        $data = firstVentaMercancia::first($request);

        return response()->json(["msg" => "Mercancia listada", "data" => $data,  "status" => true]);
    }

    public function create(Request $request)
    {
        $id = $this->ventas_mercancias_validations->createValidation($request);

        return response()->json(["msg" => "Se ha guardado la venta de la mercancia", "data" => $id, "status" => true]);
    }

    public function destroy(Request $request)
    {
        $this->ventas_mercancias_validations->destroyValidation($request);

        return response()->json(["msg" => "Se ha eliminado la venta de la mercancia",  "status" => true]);
    }

}
