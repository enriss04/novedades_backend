<?php

namespace App\Http\Proveedores;

use App\Http\Proveedores\useCases\createProveedor;
use App\Http\Proveedores\useCases\updateProveedor;
use App\Http\Proveedores\useCases\destroyProveedor;

class ProveedoresValidations {

    public function createValidation($request) 
    {

        $validatedData = $request->validate(
        [
            'nombre'        => 'required|string',
            'nombre_empresa'    => 'required|string',
            
        ]);
        $data = (object)$validatedData;        
        $data->primer_apellido  = $request->primer_apellido ?? '';
        $data->segundo_apellido = $request->segundo_apellido ?? '';
        $data->telefono         = $request->telefono ?? null;
        $data->correo           = $request->correo ?? null;

        createProveedor::save($data);
    }

    public function updateValidation($request) 
    {
        
        $validatedData = $request->validate(
        [
            'persona_id'    => 'required',
            'proveedor_id'  => 'required',        
            'nombre'        => 'required|string',
            'nombre_empresa'    => 'required|string',
        ]);
        $data = (object)$validatedData;        
        $data->primer_apellido  = $request->primer_apellido ?? '';
        $data->segundo_apellido = $request->segundo_apellido ?? '';
        $data->correo           = $request->correo ?? null;
        $data->telefono         = $request->telefono ?? null;

        updateProveedor::update($data);
       
    }

    public function destroyValidation($request) 
    {
        $request->validate(
            [
                'persona_id'    => 'required',
                'proveedor_id'  => 'required', 
            ]
        );

        destroyProveedor::destroy($request);
    }
}