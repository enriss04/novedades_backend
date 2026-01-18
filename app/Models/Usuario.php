<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Usuario
{

    public static function get($params)
    {
        $data = [];

        if ($params->get_data) {

            $object = DB::table('cuenta as C');
            $object->join('personas as P', 'P.persona_id', '=', 'C.persona_id');
            $object->select([
                'C.cuenta_id',
                'C.persona_id',
                'C.nombre as cuenta',
                'C.status as cuenta_status',
                DB::raw('CONCAT(P.nombre, " ", P.primer_apellido, " ", P.segundo_apellido) as persona_fullname'),
                'P.nombre',
                'P.primer_apellido',
                'P.segundo_apellido',
                'P.correo',
                'P.telefono'
            ]);        
            $object->whereNull('C.deleted_at');
            $data = $object->get();
        }

        return $data;
    }

    public static function save($object)
    {
        $object->inserts = $object->inserts ?? false;

        if ($object->inserts) {
            DB::table($object->table)->insert($object->data);
            $id = null;
        } else {
            $id = DB::table($object->table)->insertGetID($object->data);
        }

        return $id;
    }

    public static function update($params, $data)
    {
        DB::table($params->table)
            ->where($params->where)
            ->update($data);
    }
   
}
