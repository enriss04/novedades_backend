<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Inventario
{

    protected static $table = 'inventario';
    protected static $table_join = 'inventario AS I';
    protected static $columns = '*';
    protected static $columns_join = [
        'I.*',
    ];

    public static function getSelect($params)
    {
        $data = [];

        if ($params->get_data) {

            $object = DB::table(self::$table_join);
            $object->select([
                DB::raw("CONCAT(inventario_id, ' - ', tipo, ' - ', modelo) AS name"),
                'inventario_id AS code',
                'tipo',
                'precio_mayoreo',
                'precio_menudeo',
                'existencia',
            ]);
            $object->whereNull('deleted_at');
            if(!empty($params->validate_existencia)){
                $object->where('existencia', '>', 0);
            }
            $data = $object->get();
            
        }

        return $data;
    }

    public static function get($params)
    {
        $data = [];

        if ($params->get_data) {

            $object = DB::table(self::$table);
            $object->select(
                self::$columns
            );        
            $object->whereNull('deleted_at');
            $data = $object->get();                                  
        }

        return $data;
    }

    public static function first($params)
    {
        $data = null;

        if ($params->get_data) {

            $object = DB::table(self::$table);
            $object->select(
                self::$columns
            );
            if(!is_null($params->inventario_id)){
                $object->where('inventario_id', $params->inventario_id);
            }  
            $object->whereNull('deleted_at');
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
