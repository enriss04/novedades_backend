<?php

namespace App\Http\Pedidos;

use App\Services\SaveFile;
use Illuminate\Support\Str;

use App\Http\Pedidos\useCases\createPedido;
use App\Http\Pedidos\useCases\updatePedido;
use App\Http\Pedidos\useCases\destroyPedido;

class PedidosValidations {

    public function createValidation($request) 
    {

        $validatedData = $request->validate(
        [
            'proveedor_id'  => 'required|integer',
            'mercancias'    => 'required',            
        ]);
        $data = (object)$validatedData;

        $data->fecha        = $request->fecha ?? date('Y-m-d H:i:s');
        $data->pedido_id    = Str::uuid()->toString();
        $data->abono        = $request->abono ?? 0;
        $data->mercancias   = json_decode($request->mercancias);
        $data->url_recibo   = SaveFile::byRequest($request->file('recibo'), "pedidos/$data->pedido_id/recibo");
        $data->url_comprobante_pago = SaveFile::byRequest($request->file('comprobante'), "pedidos/$data->pedido_id/comprobante");

        createPedido::save($data);
    }

    public function updateValidation($request) 
    {
        
        $validatedData = $request->validate(
        [
            'pedido_id' => 'required|string',
            'fecha'     => 'required',
            'proveedor_id'  => 'required|integer',            
        ]);
        $data = (object)$validatedData;
        $data->url_recibo           = $request->url_recibo;
        $data->url_comprobante_pago = $request->url_comprobante_pago;

        if($request->file('recibo')){
            $data->url_recibo           = SaveFile::byRequest($request->file('recibo'), "pedidos/$data->pedido_id/recibo");
        }

        if($request->file('comprobante')){
            $data->url_comprobante_pago = SaveFile::byRequest($request->file('comprobante'), "pedidos/$data->pedido_id/comprobante");
        }

        updatePedido::update($data);
       
    }

    public function destroyValidation($request) 
    {
        $request->validate(
            [
                'pedido_id'   => 'required|string',
            ]
        );

        destroyPedido::destroy($request);
    }
}