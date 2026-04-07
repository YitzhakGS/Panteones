<?php

namespace App\Http\Controllers;

use App\Models\Finado;
use App\Services\FinadoService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Concesion;
use Illuminate\Support\Facades\Log;


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
    public function index(Request $request)
    {
        $finados = Finado::with([
            'ultimoMovimiento.concesion.lote.espaciosActuales.seccion',
            'ultimoMovimiento.concesion.lote.espaciosActuales.tipoEspacioFisico',
            'movimientos.concesion.lote.espaciosActuales.seccion',
        ])
        ->when($request->search, function ($query, $search) {
            $query->where('nombre', 'like', "%$search%")
                ->orWhere('apellido_paterno', 'like', "%$search%")
                ->orWhere('apellido_materno', 'like', "%$search%");
        })
        ->latest()
        ->paginate(10);

        $concesiones = Concesion::with([
            'lote.espaciosActuales.seccion',
            'lote.espaciosActuales.tipoEspacioFisico'
        ])->get();

        return view('finados.index', compact('finados', 'concesiones'));
    }

    // -------------------------
    // MOSTRAR
    // -------------------------
    public function show($id)
    {
        return Finado::with(['movimientos', 'ultimoMovimiento'])->findOrFail($id);
    }

    // -------------------------
    // CREAR
    // -------------------------
    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                $finado = Finado::create([
                    'nombre' => $request->nombre,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno,
                    'fecha_defuncion' => $request->fecha_defuncion,
                    'sexo' => $request->sexo,
                    'observaciones' => $request->observaciones,
                    'tipo_construccion' => $request->tipo_construccion,
                    'solicitante' => $request->solicitante, 
                ]);

                // 🔥 Inhumación inicial
                if ($request->id_concesion) {
                    $this->service->inhumar(
                        $finado->id_finado,
                        $request->id_concesion,
                        $request->all()
                    );
                }

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

                // 🔥 NUEVOS
                'solicitante',
                'tipo_construccion',
            ]));

            return redirect()->route('finados.index')
                ->with('success', 'Finado actualizado');

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // -------------------------
    // ELIMINAR
    // -------------------------
    public function destroy($id)
    {
        try {
            $finado = Finado::findOrFail($id);
            $finado->delete();

            // Redirección con mensaje de éxito
            return redirect()->route('finados.index')
                ->with('success', 'El finado ha sido eliminado correctamente.');

        } catch (\Exception $e) {
            // Logueamos el error internamente para que tú puedas revisarlo en storage/logs/laravel.log
            Log::error("Error al eliminar finado ID {$id}: " . $e->getMessage());

            // Redireccionamos al index con un mensaje de error amigable
            return redirect()->route('finados.index')
                ->with('error', 'No se pudo eliminar el registro. Es posible que tenga historial relacionado o problemas de permisos.');
        }
    }

    // =========================================================
    // 🔥 MOVIMIENTOS
    // =========================================================

    public function inhumar(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {

                $mov = $this->service->inhumar(
                    $id,
                    $request->id_concesion,
                    $request->all()
                );

                return response()->json($mov);
            });

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function exhumar(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {

                $mov = $this->service->exhumar(
                    $id,
                    $request->all()
                );

                return response()->json($mov);
            });

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function reinhumar(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {

                $mov = $this->service->reinhumar(
                    $id,
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