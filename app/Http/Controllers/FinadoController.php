<?php

namespace App\Http\Controllers;

use App\Models\Finado;
use App\Models\Concesion;
use App\Services\FinadoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CatSeccion;
use App\Models\Lote;
use Exception;

class FinadoController extends Controller
{
    protected FinadoService $service;

    public function __construct(FinadoService $service)
    {
        $this->service = $service;
    }

    // -------------------------
    // Helper: envuelve cualquier movimiento en transaction + manejo de error
    // -------------------------
    private function runMovimiento(callable $callback): JsonResponse
    {
        try {
            return DB::transaction($callback);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // -------------------------
    // LISTAR
    // -------------------------
    public function index(Request $request)
    {
        $finados = Finado::with([
            'ultimoMovimiento',
            'movimientos',
        ])
        ->when($request->search, fn($q, $s) =>
            $q->where('nombre', 'like', "%$s%")
            ->orWhere('apellido_paterno', 'like', "%$s%")
            ->orWhere('apellido_materno', 'like', "%$s%")
        )
        ->latest()
        ->paginate(10);

        $concesiones = Concesion::with([
            'titular',
            'lote.espaciosActuales.seccion',
            'lote.espaciosActuales.tipoEspacioFisico',
        ])->get();

        // 👇 ESTE ES EL NUEVO
        $secciones = CatSeccion::orderBy('nombre')->get();

        $lotes = Lote::orderBy('numero')->get();

        return view('finados.index', compact('finados', 'concesiones', 'secciones', 'lotes'));
    }

    // -------------------------
    // CREAR
    // -------------------------
    public function store(Request $request)
    {

        try {
            return DB::transaction(function () use ($request) {

                $request->validate([
                    'nombre'          => 'required|string',
                    'fecha_defuncion' => 'required|date',
                    'sexo'            => 'required|in:Masculino,Femenino',
                    'id_ubicacion_actual' => 'required|exists:concesiones,id_concesion',
                    'tipo_construccion' => 'nullable|in:cripta,capilla',
                ]);

                $finado = Finado::create($request->only([
                    'nombre',
                    'apellido_paterno',
                    'apellido_materno',
                    'fecha_defuncion',
                    'sexo',
                    'observaciones',
                    'tipo_construccion',
                ]));

                $this->service->inhumar($finado, $request->id_ubicacion_actual, $request->all());

                return redirect()->route('finados.index')
                    ->with('success', 'Finado registrado correctamente');
            });

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // -------------------------
    // ACTUALIZAR
    // -------------------------
    public function update(Request $request, Finado $finado)
    {
        try {

            // 1. actualizar datos base del finado
            $finado->update($request->only([
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'fecha_defuncion',
                'sexo',
                'observaciones',
                'tipo_construccion',
            ]));

            // 2. actualizar inhumación (concesión + fecha) si vienen en request
            if ($request->filled('id_ubicacion_actual') || $request->filled('fecha')) {

                $mov = $finado->movimientos()
                    ->where('tipo', 'inhumacion')
                    ->latest('fecha')
                    ->first();

                if ($mov) {

                    $concesion = Concesion::with([
                        'lote.espaciosActuales.seccion',
                        'lote.espaciosActuales.tipoEspacioFisico',
                    ])->findOrFail($request->id_ubicacion_actual);

                    $mov->update([
                        'id_ubicacion_actual' => $request->id_ubicacion_actual,
                        'ubicacion_actual'    => app(FinadoService::class)
                            ->formatearUbicacion($concesion),
                        'fecha'               => $request->fecha,
                    ]);
                }
            }

            return redirect()->route('finados.index')
                ->with('success', 'Finado actualizado');

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // -------------------------
    // ELIMINAR
    // -------------------------
    public function destroy(Finado $finado)
    {
        try {
            $finado->delete();

            return redirect()->route('finados.index')
                ->with('success', 'El finado ha sido eliminado correctamente.');

        } catch (Exception $e) {
            Log::error("Error al eliminar finado ID {$finado->id_finado}: " . $e->getMessage());

            return redirect()->route('finados.index')
                ->with('error', 'No se pudo eliminar el registro. Es posible que tenga historial relacionado.');
        }
    }

    // =========================================================
    // MOVIMIENTOS — usan route model binding + helper
    // =========================================================

    public function inhumar(Request $request, Finado $finado)
    {
        return $this->runMovimiento(fn() =>
            response()->json(
                $this->service->inhumar($finado, $request->id_concesion, $request->all())
            )
        );
    }

    public function exhumar(Request $request, Finado $finado)
    {
        return $this->runMovimiento(fn() =>
            response()->json(
                $this->service->exhumar($finado, $request->all())
            )
        );
    }

    public function reinhumar(Request $request, Finado $finado)
    {
        return $this->runMovimiento(fn() =>
            response()->json(
                $this->service->reinhumar($finado, $request->id_concesion, $request->all())
            )
        );
    }

    public function mover(Request $request, Finado $finado)
    {
        return $this->runMovimiento(fn() =>
            response()->json(
                $this->service->mover($finado, $request->id_ubicacion_actual, $request->all())
            )
        );
    }
}