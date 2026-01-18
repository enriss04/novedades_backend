<?php

namespace App\Http\VentasMercancias;

use App\Http\VentasMercancias\useCases\createVentaMercancia;
use App\Http\VentasMercancias\useCases\destroyVentaMercancia;

class VentasMercanciasValidations {

    public function createValidation($request) 
    {

        $validatedData = $request->validate(
        [
            'venta_id'      => 'required|string',
            'inventario_id' => 'required|string',
            'tipo_venta'    => 'required|string',
            'cantidad'      => 'required|integer',
            'precio_unitario'   => 'required|numeric',
            'descuento'         => 'required|numeric',
        ]);
        $data = (object)$validatedData;        

        return createVentaMercancia::save($data);
    }

    public function destroyValidation($request) 
    {
        $request->validate(
            [
                'venta_mercancia_id'    => 'required|integer',
                'inventario_id'         => 'required|string',                
                'venta_id'              => 'required|string',
                'cantidad'              => 'required|integer',                
            ]
        );

        destroyVentaMercancia::destroy($request);
    }
}