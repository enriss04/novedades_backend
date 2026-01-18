<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class VentaMercancia
{

    protected static $table = 'ventas_mercancias';
    protected static $table_join = 'ventas_mercancias AS VM';
    protected static $columns = '*';
    protected static $columns_join = [
        'VM.venta_mercancia_id',
        'VM.inventario_id',
        'VM.tipo',
        'VM.cantidad',
        'VM.precio_unitario',
        'VM.descuento',
        'VM.subtotal',
        'VM.total',
        'I.talla',
        'I.color',
        'I.modelo',
        'I.tipo as tipo_inventario',
        'I.precio_mayoreo',
        'I.precio_menudeo',
        'I.existencia',
        'I.ingreso',
        'I.salida',
        'I.devoluciones',
        'I.url',
    ];

    public static function get($params)
    {
        $data = [];

        if ($params->get_data) {

            $object = DB::table(self::$table_join);
            $object->leftjoin('inventario AS I', 'VM.inventario_id', '=', 'I.inventario_id');
            $columns = self::$columns_join;
            $columns[] = DB::raw("CONCAT(I.modelo, ' - ', I.tipo) AS mercancia_nombre");
            $object->select(
                $columns
            );
            if(!is_null($params->venta_id)){
                $object->where('VM.venta_id', $params->venta_id);
            }
            if(!is_null($params->inventario_id)){
                $object->where('VM.inventario_id', $params->inventario_id);
            }
            $object->whereNull('VM.deleted_at');
            $data = $object->get();                                  
        }

        return $data;
    }

    public static function first($params)
    {
        $data = null;

        if ($params->get_data) {

            $object = DB::table(self::$table_join);
            $object->leftjoin('inventario AS I', 'VM.inventario_id', '=', 'I.inventario_id');
            $object->select(
                self::$columns_join
            );
            if(!is_null($params->venta_id)){
                $object->where('VM.venta_id', $params->venta_id);
            }
            if(!is_null($params->inventario_id)){
                $object->where('VM.inventario_id', $params->inventario_id);
            }
            $object->whereNull('VM.deleted_at');
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
