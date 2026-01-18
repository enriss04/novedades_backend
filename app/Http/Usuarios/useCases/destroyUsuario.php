<?php

namespace App\Http\Usuarios\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Usuario;

class destroyUsuario{

    public static function destroy($data)
    {
        try {
            DB::beginTransaction();

            $date = date('Y-m-d H:i:s');

            Usuario::update(
                (object)[
                    "table" => "cuenta",
                    "where" => [["cuenta_id", $data->cuenta_id]]
                ],
                [                    
                    "deleted_at"    => $date
                ]
            );
            
            Usuario::update(
                (object)[
                    "table" => "personas",
                    "where" => [["persona_id", $data->persona_id]]
                ],
                [                    
                    "deleted_at"    => $date
                ]
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al eliminar al usuario",
                404,
                $e
            );
        }
    }
}