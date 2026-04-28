<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Concesion;
use App\Models\Lote;
use App\Models\Titular;
use App\Models\CatUsoFunerario;
use App\Models\CatEstatus;
use App\Services\ConcesionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ConcesionController extends Controller
{
    public function __construct(protected ConcesionService $concesionService) {}

    /**
     * Listado de concesiones
     */
    public function index(Request $request): View
    {
        $query = Concesion::with([
            'lote',
            'titular',
            'usoFunerario',
            'estatus',
            'ultimoRefrendo',
        ]);

        if ($request->filled('search')) {

            $search = Str::of($request->search)
                ->lower()
                ->ascii() // quita acentos
                ->trim();

            $words = collect(explode(' ', $search))->filter();

            $query->where(function ($q) use ($words) {

                foreach ($words as $word) {

                    $q->where(function ($sub) use ($word) {

                        $like = "%{$word}%";

                        $sub->whereHas('lote', fn($q) =>
                                $q->whereRaw('LOWER(numero) LIKE ?', [$like])
                            )
                            ->orWhereHas('titular', fn($q) =>
                                $q->whereRaw('LOWER(familia) LIKE ?', [$like])
                            )
                            ->orWhereHas('estatus', fn($q) =>
                                $q->whereRaw('LOWER(nombre) LIKE ?', [$like])
                            )
                            ->orWhereHas('usoFunerario', fn($q) =>
                                $q->whereRaw('LOWER(nombre) LIKE ?', [$like])
                            );
                    });

                }

            });
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro rápido de alertas
        if ($request->filled('alerta')) {
            match($request->alerta) {
                'adeudo' => $query->conAdeudo(),
                'riesgo' => $query->enRiesgo(),
                default  => null,
            };
        }

        $concesiones = $query
            ->orderBy('id_concesion', 'desc')
            ->paginate(15)
            ->withQueryString();

        $lotes     = Lote::orderBy('numero')->get();
        $titulares = Titular::orderBy('familia')->get();
        $usos      = CatUsoFunerario::orderBy('nombre')->get();

        return view('concesiones.index', compact(
            'concesiones',
            'lotes',
            'titulares',
            'usos'
        ));
    }

    /**
     * Formulario crear concesión
     */
    public function create(): View
    {
        return view('concesiones.create', [
            'lotes'     => Lote::orderBy('numero')->get(),
            'titulares' => Titular::orderBy('familia')->get(),
            'usos'      => CatUsoFunerario::orderBy('nombre')->get(),
        ]);
    }

    /**
     * Guardar concesión
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_lote'           => 'required|exists:lotes,id_lote',
            'id_titular'        => 'required|exists:titulares,id_titular',
            'id_uso_funerario'  => 'required|exists:uso_funerario,id_uso_funerario',
            'tipo'              => 'required|in:temporal,perpetuidad',
            'fecha_inicio'      => 'required|date',
            'monto'             => 'nullable|numeric|min:0',
            'fecha_limite_pago' => 'nullable|date|after:fecha_inicio',
            'tipo_refrendo'     => 'nullable|in:mantenimiento,administrativo',
            'observaciones'     => 'nullable|string',
        ]);

        $this->concesionService->crear($data);

        return redirect()
            ->route('concesiones.index')
            ->with('success', 'Concesión creada correctamente.');
    }

    /**
     * Ver concesión
     */
    public function show(Concesion $concesion): mixed
    {
        $concesion->load([
            'lote',
            'titular',
            'usoFunerario',
            'estatus',
            'ultimoRefrendo',
            'refrendos',
        ]);

        return view('concesiones.show', compact('concesion'));
    }

    /**
     * Formulario editar concesión
     */
    public function edit(Concesion $concesion): View
    {
        return view('concesiones.edit', [
            'concesion' => $concesion,
            'lotes'     => Lote::orderBy('numero')->get(),
            'titulares' => Titular::orderBy('familia')->get(),
            'usos'      => CatUsoFunerario::orderBy('nombre')->get(),
        ]);
    }

    /**
     * Actualizar concesión
     */
    public function update(Request $request, Concesion $concesion): RedirectResponse
    {
        $data = $request->validate([
            'id_lote'           => 'required|exists:lotes,id_lote',
            'id_titular'        => 'required|exists:titulares,id_titular',
            'id_uso_funerario'  => 'required|exists:uso_funerario,id_uso_funerario',
            'tipo'              => 'required|in:temporal,perpetuidad',
            'fecha_inicio'      => 'required|date',
            'observaciones'     => 'nullable|string',
            'monto'             => 'nullable|numeric|min:0',
            'fecha_limite_pago' => 'nullable|date',
        ]);

        $this->concesionService->actualizar($concesion, $data);

        return redirect()
            ->route('concesiones.index')
            ->with('success', 'Concesión actualizada correctamente.');
    }

    /**
     * Cancelar concesión (sin eliminar)
     */
    public function cancelar(Request $request, Concesion $concesion): RedirectResponse
    {
        $request->validate([
            'observaciones' => 'nullable|string',
        ]);

        $this->concesionService->cancelar($concesion, $request->observaciones);

        return redirect()
            ->route('concesiones.index')
            ->with('success', 'Concesión cancelada correctamente.');
    }

    /**
     * Eliminar (soft delete)
     */
    public function destroy(Concesion $concesion): RedirectResponse
    {
        $concesion->delete();

        return redirect()
            ->route('concesiones.index')
            ->with('success', 'Concesión eliminada.');
    }

    /**
     * Endpoint JSON para modales/JS — única fuente de verdad
     */
    public function getData(Concesion $concesion): JsonResponse
    {
        $concesion->load([
            'lote',
            'titular',
            'usoFunerario',
            'estatus',
            'ultimoRefrendo',
        ]);

        return response()->json([
            'id_concesion'     => $concesion->id_concesion,
            'id_lote'          => $concesion->id_lote,
            'id_titular'       => $concesion->id_titular,
            'id_uso_funerario' => $concesion->id_uso_funerario,
            'tipo'             => $concesion->tipo,
            'fecha_inicio'     => $concesion->fecha_inicio->format('Y-m-d'),
            'fecha_fin'        => $concesion->fecha_fin?->format('Y-m-d'),
            'observaciones'    => $concesion->observaciones,
            'estatus'          => $concesion->estatus?->nombre,
            'esta_vencida'     => $concesion->esta_vencida,
            'anos_en_adeudo'   => $concesion->anos_en_adeudo,
            'monto'            => $concesion->ultimoRefrendo?->monto ?? 0,
            'fecha_limite_pago' => $concesion->ultimoRefrendo?->fecha_limite_pago?->format('Y-m-d'),
        ]);
    }


}