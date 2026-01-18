<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Proveedor
{

    protected static $table = 'proveedores';
    protected static $table_join = 'proveedores AS P';
    protected static $columns = '*';
    protected static $columns_join = [
        'P.proveedor_id',
        'P.nombre as nombre_empresa',
        'PE.persona_id',
        'PE.nombre',
        'PE.primer_apellido',
        'PE.segundo_apellido',
        'PE.nombre_completo',
        'PE.correo',
        'PE.telefono',
    ];

    public static function getSelect($params)
    {
        $data = [];

        if ($params->get_data) {

            $data = DB::table(self::$table_join)
            ->leftjoin('personas AS PE', 'P.encargado_persona_id', '=', 'PE.persona_id')
            ->select([
                DB::raw("CONCAT(PE.nombre, ' - ', P.nombre) AS name"),
                'P.proveedor_id AS code',
            ])
            ->whereNull('P.deleted_at')
            ->get();
            
        }

        return $data;
    }

    public static function get($params)
    {
        $data = [];

        if ($params->get_data) {

            $object = DB::table(self::$table_join);
            $object->leftjoin('personas AS PE', 'P.encargado_persona_id', '=', 'PE.persona_id');
            $object->select(
                self::$columns_join
            );        
            $object->whereNull('P.deleted_at');
            $data = $object->get();                                  
        }

        return $data;
    }

    public static function first($params)
    {
        $data = null;

        if ($params->get_data) {

            $object = DB::table(self::$table_join);
            $object->leftjoin('personas AS PE', 'P.encargado_persona_id', '=', 'PE.persona_id');
            $object->select(
                self::$columns_join
            );
            if(!is_null($params->persona_id)){
                $object->where('P.persona_id', $params->persona_id);
            }
            $object->whereNull('P.deleted_at');
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
