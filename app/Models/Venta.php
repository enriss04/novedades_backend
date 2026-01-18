<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Venta
{

    protected static $table = 'ventas';
    protected static $table_join = 'ventas AS V';
    protected static $columns = ['*'];
    protected static $columns_join = [
        'V.*',
    ];

    public static function get($params)
    {
        $data = [];

        if ($params->get_data) {

            $object = DB::table(self::$table);
            $columns = self::$columns;
            $columns[] = DB::raw("DATE_FORMAT(fecha, '%d/%m/%Y') as fecha");
            $columns[] = DB::raw("DATE_FORMAT(created_at, '%d/%m/%Y %H:%i:%s') as fecha_creacion");
            $object->select(
                $columns
            );        
            $object->whereNull('deleted_at');
            $object->orderBy('fecha', 'desc');
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
