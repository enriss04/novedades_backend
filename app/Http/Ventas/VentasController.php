<?php

namespace App\Http\Ventas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Ventas\VentasValidations;

use App\Models\Dashboard;
use App\Models\Inventario;
use App\Http\Ventas\useCases\getVenta;
use App\Http\Ventas\useCases\firstVenta;
use App\Http\VentasMercancias\useCases\getVentaMercancia;

class VentasController extends Controller
{

    protected $ventas_validations;

    public function __construct(VentasValidations $ventas_validations)
    {
        $this->ventas_validations = $ventas_validations;
    }

    public function select(Request $request)
    {
        $request->validate_existencia = true;
        $inventario = Inventario::getSelect($request);

        return response()->json(["msg" => "Mercancias listadas", "data" => [
            "inventario" => $inventario,
        ],  "status" => true]);
    }

    public function get(Request $request)
    {
        $data = getVenta::get($request);
        
        $dashboard = Dashboard::getTotalVentas((object)[
            'table' => 'ventas', 
            'get_data' => $request->get_data
        ]);

        return response()->json(["msg" => "Ventas listadas", "data" => [
            "data" => $data, 
            "dashboard" => $dashboard
        ],  "status" => true]);
    }

    public function first(Request $request)
    {
        $venta = firstVenta::first($request);
        $mercancias = getVentaMercancia::get($request);

        return response()->json(["msg" => "Venta listada", "data" => [
            "venta" => $venta,
            "mercancias" => $mercancias
        ],  "status" => true]);
    }

    public function create(Request $request)
    {
        $this->ventas_validations->createValidation($request);

        return response()->json(["msg" => "Se ha guardado la venta",  "status" => true]);
    }

    public function update(Request $request)
    {
        $this->ventas_validations->updateValidation($request);

        return response()->json(["msg" => "Se ha actualizado la venta",  "status" => true]);
    }

    public function destroy(Request $request)
    {
        $this->ventas_validations->destroyValidation($request);

        return response()->json(["msg" => "Se ha eliminado la venta",  "status" => true]);
    }

}
