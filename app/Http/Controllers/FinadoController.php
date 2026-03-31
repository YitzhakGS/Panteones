<?php

namespace App\Http\Controllers;

use App\Models\Finado;
use App\Services\FinadoService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class FinadoController extends Controller
{
    protected FinadoService $service;

    public function __construct(FinadoService $service)
    {
        $this->service = $service;
    }

    // -------------------------
    // LISTAR
    // -------------------------
    public function index()
    {
        return Finado::with('movimientos')->latest()->get();
    }

    // -------------------------
    // MOSTRAR
    // -------------------------
    public function show($id)
    {
        return Finado::with('movimientos')->findOrFail($id);
    }

    // -------------------------
    // CREAR
    // -------------------------
    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                // 1. Crear finado
                $finado = Finado::create([
                    'nombre' => $request->nombre,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno,
                    'fecha_defuncion' => $request->fecha_defuncion,
                    'sexo' => $request->sexo,
                    'observaciones' => $request->observaciones,
                ]);

                // 2. Inhumar automáticamente
                if ($request->id_concesion) {
                    $this->service->inhumar(
                        $finado->id_finado,
                        $request->id_concesion,
                        [
                            'fecha' => $request->fecha ?? now(),
                            'observaciones' => 'Registro inicial',
                        ]
                    );
                }

                return response()->json($finado, 201);
            });

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // -------------------------
    // ACTUALIZAR
    // -------------------------
    public function update(Request $request, $id)
    {
        try {
            $finado = Finado::findOrFail($id);

            $finado->update($request->only([
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'fecha_defuncion',
                'sexo',
                'observaciones',
            ]));

            return response()->json($finado);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // -------------------------
    // ELIMINAR (soft delete)
    // -------------------------
    public function destroy($id)
    {
        try {
            $finado = Finado::findOrFail($id);
            $finado->delete();

            return response()->json(['message' => 'Finado eliminado']);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // =========================================================
    // 🔥 ACCIONES RELACIONADAS A MOVIMIENTOS
    // =========================================================

    public function inhumar(Request $request, $id)
    {
        try {
            $mov = $this->service->inhumar(
                $id,
                $request->id_concesion,
                $request->all()
            );

            return response()->json($mov);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function exhumar(Request $request, $id)
    {
        try {
            $mov = $this->service->exhumar(
                $id,
                $request->all()
            );

            return response()->json($mov);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function reinhumar(Request $request, $id)
    {
        try {
            $mov = $this->service->reinhumar(
                $id,
                $request->id_concesion,
                $request->all()
            );

            return response()->json($mov);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}