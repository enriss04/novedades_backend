<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Pedido
{

    protected static $table = 'pedidos';
    protected static $table_join = 'pedidos AS PD';
    protected static $columns = '*';
    protected static $columns_join = [
        'PD.pedido_id',
        'PD.proveedor_id',
        'PD.descuento',
        'PD.subtotal',
        'PD.total',
        'PD.abono',
        'PD.status',
        'PD.fecha',
        'PD.url_comprobante_pago',
        'PD.url_recibo',
        'PE.nombre_completo',
        'PV.nombre as empresa',
        'PE.correo',
        'PE.telefono',
    ];

    public static function get($params)
    {
        $data = [];

        if ($params->get_data) {
            $object = DB::table(self::$table_join);
            $object->leftjoin('proveedores AS PV', 'PD.proveedor_id', '=', 'PV.proveedor_id');
            $object->leftjoin('personas AS PE', 'PV.encargado_persona_id', '=', 'PE.persona_id');
            $columns = self::$columns_join;
            $columns[] = DB::raw("DATE_FORMAT(PD.fecha, '%d/%m/%Y') as fecha");
            $columns[] = DB::raw("DATE_FORMAT(PD.created_at, '%d/%m/%Y %H:%i:%s') as fecha_creacion");
            $object->select($columns);
            $object->whereNull('PD.deleted_at');
            $object->orderBy('PD.fecha', 'desc');
            $data = $object->get();
        }

        return $data;
    }

    public static function first($params)
    {
        $data = null;

        if ($params->get_data) {
            $object = DB::table(self::$table_join);
            $object->leftjoin('proveedores AS PV', 'PD.proveedor_id', '=', 'PV.proveedor_id');
            $object->leftjoin('personas AS PE', 'PV.encargado_persona_id', '=', 'PE.persona_id');
            $object->select(
                self::$columns_join
            );
            if(!is_null($params->pedido_id)){
                $object->where('PD.pedido_id', $params->pedido_id);
            }
            $object->whereNull('PD.deleted_at');
            $data = $object->first();
        }

        return $data;
    }

    public static function save($object)
    {
        $object->inserts = $object->inserts ?? false;

        if ($object->inserts) {
            DB::table(self::$table)->insert($object->data);
            $id = null;
        } else {
            $id = DB::table(self::$table)->insertGetID($object->data);
        }

        return $id;
    }

    public static function update($params, $data)
    {
        DB::table(self::$table)
            ->where($params)
            ->update($data);
    }
   
}
