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
            'tipo'          => 'required|string',
        ]);

        $data = (object)$validatedData;        
        $data->url      = SaveFile::byRequest($request->file('archivo'), "inventario/$data->inventario_id");
        $data->talla    = $request->talla ?? '';
        $data->color    = $request->color ?? '';
        $data->modelo   = $request->modelo ?? '';
        $data->salida   = $request->salida ?? 0;
        $data->ingreso  = $request->ingreso ?? 0;
        $data->existencia   = $request->existencia ?? 0;
        $data->devoluciones = $request->devoluciones ?? 0;
        $data->precio_mayoreo   = $request->precio_mayoreo === 'null' ? 0 : $request->precio_mayoreo;
        $data->precio_menudeo   = $request->precio_menudeo === 'null' ? 0 : $request->precio_menudeo;

        createInventario::save($data);
    }

    public function updateValidation($request) 
    {
        
        $validatedData = $request->validate(
        [
            'inventario_id' => 'required|string',
            'tipo'          => 'required|string',
        ]);

        $data = (object)$validatedData;
        $data->url          = SaveFile::byRequest($request->file('archivo'), "inventario/$data->inventario_id");
        $data->talla    = $request->talla ?? '';
        $data->color    = $request->color ?? '';
        $data->modelo   = $request->modelo ?? '';
        $data->salida   = $request->salida ?? 0;
        $data->ingreso  = $request->ingreso ?? 0;
        $data->existencia   = $request->existencia ?? 0;
        $data->devoluciones = $request->devoluciones ?? 0;
        $data->precio_mayoreo   = $request->precio_mayoreo === 'null' ? 0 : $request->precio_mayoreo;
        $data->precio_menudeo   = $request->precio_menudeo === 'null' ? 0 : $request->precio_menudeo;

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