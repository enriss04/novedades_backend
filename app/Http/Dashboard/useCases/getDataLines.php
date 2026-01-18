<?php

namespace App\Http\Dashboard\useCases;

use App\Exceptions\CustomError;

use App\Models\Dashboard;

class getDataLines
{

    public static function get($request)
    {
        try {

            $params = (object)[
                'get_data'  => $request->get_data ?? false,
                'year'    => $request->year ?? date('Y'),
            ];

            $data = Dashboard::dataLines($params);

            return $data;
        } catch (\Exception $e) {

            throw new CustomError(
                "Ocurrio un error al obtener la información",
                404,
                $e
            );

        }
    }

}
