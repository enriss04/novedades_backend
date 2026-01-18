<?php

namespace App\Http\Ventas;

use Illuminate\Support\Str;

use App\Http\Ventas\useCases\createVenta;
use App\Http\Ventas\useCases\updateVenta;
use App\Http\Ventas\useCases\destroyVenta;

class VentasValidations {

    public function createValidation($request) 
    {

        $validatedData = $request->validate(
        [
            'mercancias'    => 'required',            
        ]);
        $data = (object)$validatedData;

        $data->venta_id     = Str::uuid()->toString();
        $data->fecha        = $request->fecha ?? date('Y-m-d H:i:s');
        $data->comentario   = $request->comentario ?? null;
        $data->mercancias   = json_decode($request->mercancias);

        createVenta::save($data);
    }

    public function updateValidation($request) 
    {
        
        $validatedData = $request->validate(
        [
            'venta_id'  => 'required|string',
            'fecha'     => 'required',
        ]);
        $data = (object)$validatedData;
        $data->comentario   = $request->comentario ?? null;

        updateVenta::update($data);
       
    }

    public function destroyValidation($request) 
    {
        $request->validate(
            [
                'venta_id'   => 'required|string',
            ]
        );

        destroyVenta::destroy($request);
    }
}