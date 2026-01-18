<?php

namespace App\Http\Dashboard\useCases;

use App\Exceptions\CustomError;

use App\Models\Dashboard;

class getDashboard
{

    public static function get($request)
    {
        try {

            $params = (object)[
                'get_data'  => $request->get_data ?? false,
                'filtro'    => $request->filtro ?? 'mes',
            ];

            $table = Dashboard::getGanaciaMercancia($params);
           
            $colorsCircle = Dashboard::getTotalByPropieties((object)[
                'get_data'  => $params->get_data,
                'filtro'    => $params->filtro,
                'property'  => 'i.color'
            ]);

            $sizeCircle = Dashboard::getTotalByPropieties((object)[
                'get_data'  => $params->get_data,
                'filtro'    => $params->filtro,
                'property'  => 'i.talla'
            ]);
            
            $typesBar = Dashboard::getTypesBar($params);

            return [
                'years' => Dashboard::getYear(),
                'table' => $table,
                'colorsCircle' => $colorsCircle,
                'sizeCircle' => $sizeCircle,
                'typesBar' => $typesBar
            ];

        } catch (\Exception $e) {

            throw new CustomError(
                "Ocurrio un error al obtener la información",
                404,
                $e
            );

        }
    }

}
