<?php

namespace App\Http\Controllers;

use App\Services\ReporteFinadoService;
use Illuminate\Http\Request;

class ReporteFinadoController extends Controller
{
    public function __construct(protected ReporteFinadoService $service) {}

    public function exhumaciones(Request $request)
    {
        $query = $request->query();
        
        $filtros = $request->only(['fecha_inicio', 'fecha_fin']);
        $datos   = $this->service->reporteExhumaciones($filtros);

        return view('reportes.exhumaciones', compact('datos', 'filtros'));
    }

    public function concesiones(Request $request)
    {
        $filtros = $request->only(['fecha_inicio', 'fecha_fin']);
        $datos   = $this->service->reporteConcesiones($filtros);

        return view('reportes.concesiones', compact('datos', 'filtros'));
    }
}