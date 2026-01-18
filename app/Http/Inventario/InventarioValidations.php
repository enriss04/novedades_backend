<?php

namespace App\Http\Inventario;

use App\Services\SaveFile;
use Illuminate\Support\Str;

use App\Http\Inventario\useCases\createInventario;
use App\Http\Inventario\useCases\updateInventario;
use App\Http\Inventario\useCases\destroyInventario;

class InventarioValidations {

    public function createValidation($request) 
    {

        $validatedData = $request->validate(
        [
            'inventario_id'   => 'required|string',
            'talla'         => 'required|string',
            'color'         => 'required|string',
            'modelo'        => 'required|string',
            'tipo'          => 'required|string',
            'precio_mayoreo'    => 'required|numeric',
            'precio_menudeo'    => 'required|numeric',  
        ]);

        $data = (object)$validatedData;        
        $data->url          = SaveFile::byRequest($request->file('archivo'), "inventario/$data->inventario_id");
        $data->existencia   = $request->existencia ?? 0;
        $data->ingreso      = $request->ingreso ?? 0;
        $data->salida       = $request->salida ?? 0;
        $data->devoluciones = $request->devoluciones ?? 0;

        createInventario::save($data);
    }

    public function updateValidation($request) 
    {
        
        $validatedData = $request->validate(
        [
            'inventario_id'   => 'required|string',
            'talla'         => 'required|string',
            'color'         => 'required|string',
            'modelo'        => 'required|string',
            'tipo'          => 'required|string',
            'precio_mayoreo'    => 'required|numeric',
            'precio_menudeo'    => 'required|numeric',           
        ]);

        $data = (object)$validatedData;
        $data->url          = SaveFile::byRequest($request->file('archivo'), "inventario/$data->inventario_id");

        updateInventario::update($data);
       
    }

    public function destroyValidation($request) 
    {
        $request->validate(
            [
                'inventario_id'   => 'required|string',
            ]
        );

        destroyInventario::destroy($request);
    }
}