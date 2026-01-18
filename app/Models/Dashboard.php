<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard
{

    public static function getYear(){
        $years = DB::table('ventas')
        ->selectRaw('YEAR(fecha) as anio')
        ->distinct()
        ->orderBy('anio', 'desc')
        ->pluck('anio')    
        ->map(fn($year) => (string) $year)    
        ->toArray();

        return $years;
    }

    public static function getTotalVentas($params)
    {
        $data = (object)[
            "hoy"       => 0,
            "semana"    => 0,
            "mes"       => 0,
        ];

        if ($params->get_data) {

            $data = DB::table($params->table)
            ->selectRaw("
                SUM(CASE WHEN DATE(fecha) = CURRENT_DATE THEN total ELSE 0 END) as hoy,
                SUM(CASE WHEN fecha >= ? THEN total ELSE 0 END) as semana,
                SUM(CASE WHEN MONTH(fecha) = MONTH(CURRENT_DATE) AND YEAR(fecha) = YEAR(CURRENT_DATE) THEN total ELSE 0 END) as mes
            ", [Carbon::now()->startOfWeek()])
            ->first();
            
        }

        return $data;
    }

    public static function getGanaciaMercancia($params)
    {
        $data = [];

        if ($params->get_data) {

            $ventasFiltradas = DB::table('ventas_mercancias as vm')
                ->join('ventas as v', 'vm.venta_id', '=', 'v.venta_id')
                ->select('vm.inventario_id', 
                    DB::raw('SUM(vm.cantidad) as total_piezas'),
                    DB::raw('SUM(vm.total) as total_dinero')
                )
                ->when($params->filtro == 'dia', fn($q) => $q->whereDate('v.fecha', Carbon::today()))
                ->when($params->filtro == 'mes', fn($q) => $q->whereMonth('v.fecha', Carbon::now()->month)->whereYear('v.fecha', Carbon::now()->year))
                ->when($params->filtro == 'anio', fn($q) => $q->whereYear('v.fecha', Carbon::now()->year))
                ->groupBy('vm.inventario_id');

            // 2. Subconsulta de COSTOS (Promedio histórico de compra)
            $costosPromedio = DB::table('pedidos_mercancias')
                ->select('inventario_id', DB::raw('AVG(precio_unitario) as costo_u'))
                ->groupBy('inventario_id');

            // 3. Unión Final
            $data = DB::table('inventario as i')
                ->joinSub($ventasFiltradas, 'v', function ($join) {
                    $join->on('i.inventario_id', '=', 'v.inventario_id');
                })
                ->leftJoinSub($costosPromedio, 'c', function ($join) {
                    $join->on('i.inventario_id', '=', 'c.inventario_id');
                })
                ->select(
                    'i.modelo',
                    'v.total_piezas',
                    'v.total_dinero as ingresos',
                    // Costo = Piezas vendidas hoy * Su costo promedio de compra
                    DB::raw('(v.total_piezas * IFNULL(c.costo_u, 0)) as costo_total'),
                    // Ganancia = Ingresos - Costo Total
                    DB::raw('(v.total_dinero - (v.total_piezas * IFNULL(c.costo_u, 0))) as ganancia')
                )
                ->get();
            
        }

        return $data;
    }

    public static function getTotalByPropieties($params)
    {
        $data = (object)[
            'titles' => [],
            'totals' => [],
        ];

        if ($params->get_data) {

            $reporte = DB::table('ventas_mercancias as vm')
                ->join('ventas as v', 'vm.venta_id', '=', 'v.venta_id')
                ->join('inventario as i', 'vm.inventario_id', '=', 'i.inventario_id')
                ->select(
                    "$params->property as property",
                    DB::raw('SUM(vm.cantidad) as total')
                )                
                ->when($params->filtro == 'dia', fn($q) => $q->whereDate('v.fecha', Carbon::today()))
                ->when($params->filtro == 'mes', fn($q) => $q->whereMonth('v.fecha', Carbon::now()->month)->whereYear('v.fecha', Carbon::now()->year))
                ->when($params->filtro == 'anio', fn($q) => $q->whereYear('v.fecha', Carbon::now()->year))            
                ->groupBy($params->property)
                ->orderBy($params->property, 'asc')
                ->get();
            
            $data->titles = $reporte->pluck('property');
            $data->totals = $reporte->pluck('total');
        }

        return $data;
    }

    public static function getTypesBar($params)
    {
        $data = (object)[
            'titles' => [],
            'mayoreo' => [],
            'menudeo' => [],
        ];

        if ($params->get_data) {

            $reporte = DB::table('inventario as i')
                ->join('ventas_mercancias as vm', 'i.inventario_id', '=', 'vm.inventario_id')
                ->join('ventas as v', 'vm.venta_id', '=', 'v.venta_id')
                ->select(
                    'i.modelo',                    
                    DB::raw("SUM(CASE WHEN vm.tipo = 'Mayoreo' THEN vm.cantidad ELSE 0 END) as cantidad_mayoreo"),                    
                    DB::raw("SUM(CASE WHEN vm.tipo = 'Menudeo' THEN vm.cantidad ELSE 0 END) as cantidad_menudeo"),      
                )
                ->when($params->filtro == 'dia', fn($q) => $q->whereDate('v.fecha', Carbon::today()))
                ->when($params->filtro == 'mes', fn($q) => $q->whereMonth('v.fecha', Carbon::now()->month)->whereYear('v.fecha', Carbon::now()->year))
                ->when($params->filtro == 'anio', fn($q) => $q->whereYear('v.fecha', Carbon::now()->year))
                ->groupBy('i.modelo')
                ->get();
            
            $data->titles = $reporte->pluck('modelo');
            $data->mayoreo = $reporte->pluck('cantidad_mayoreo');
            $data->menudeo = $reporte->pluck('cantidad_menudeo');
        }

        return $data;
    }


      public static function dataLines($params)
    {
        $data = [
            'ventas'  => [],
            'pedidos' => [],
        ];

        if ($params->get_data) {
            $ventasRaw = DB::table('ventas_mercancias')
                ->selectRaw('MONTH(created_at) as mes, SUM(total) as total')
                ->whereYear('created_at', $params->year)
                ->groupBy('mes')
                ->pluck('total', 'mes');

            $pedidosRaw = DB::table('pedidos_mercancias')
                ->selectRaw('MONTH(created_at) as mes, SUM(total) as total')
                ->whereYear('created_at', $params->year)
                ->groupBy('mes')
                ->pluck('total', 'mes');

            $arregloVentas = [];
            $arregloPedidos = [];

            for ($i = 1; $i <= 12; $i++) {
                $arregloVentas[] = $ventasRaw->get($i, 0);
                $arregloPedidos[] = $pedidosRaw->get($i, 0);
            }
        }

        $data['ventas'] = $arregloVentas;
        $data['pedidos'] = $arregloPedidos;

        return $data;
    }
}
