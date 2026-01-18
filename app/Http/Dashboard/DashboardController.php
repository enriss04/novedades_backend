<?php

namespace App\Http\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Dashboard\useCases\getDashboard;
use App\Http\Dashboard\useCases\getDataLines;

class DashboardController extends Controller
{
    public function get(Request $request)
    {
        $data = getDashboard::get($request);

        return response()->json(["msg" => "Dashboard listado", "data" => $data,  "status" => true]);
    }

    public function getDataLines(Request $request)
    {
        $data = getDataLines::get($request);

        return response()->json(["msg" => "Lineas", "data" => $data,  "status" => true]);
    }
}
