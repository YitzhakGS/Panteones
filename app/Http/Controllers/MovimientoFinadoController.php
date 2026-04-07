<?php

namespace App\Http\Controllers;

use App\Services\FinadoService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class MovimientoFinadoController extends Controller
{
    protected FinadoService $service;

    public function __construct(FinadoService $service)
    {
        $this->service = $service;
    }

    // -------------------------
    // INHUMAR
    // -------------------------
    public function inhumar(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                $mov = $this->service->inhumar(
                    $request->id_finado,
                    $request->id_concesion,
                    $request->all()
                );

                return response()->json($mov);
            });

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // -------------------------
    // EXHUMAR
    // -------------------------
    public function exhumar(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                $mov = $this->service->exhumar(
                    $request->id_finado,
                    $request->all()
                );

                return response()->json($mov);
            });

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // -------------------------
    // REINHUMAR
    // -------------------------
    public function reinhumar(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                $mov = $this->service->reinhumar(
                    $request->id_finado,
                    $request->id_concesion,
                    $request->all()
                );

                return response()->json($mov);
            });

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}