<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Refrendo;
use App\Models\Concesion;
use App\Models\ConfigGlobal;
use App\Services\RefrendoService;

class RefrendoController extends Controller
{
    public function __construct(protected RefrendoService $refrendoService) {}

    /**
     * Listado de refrendos
     */
    public function index(Request $request): View
    {
        $query = Refrendo::with([
            'concesion.lote',
            'concesion.titular',
            'pago',
        ]);

        // Filtro por tipo
        if ($request->filled('tipo_refrendo')) {
            $query->porTipo($request->tipo_refrendo);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            match($request->estado) {
                'pendientes' => $query->pendientes(),
                'vencidos'   => $query->vencidos(),
                'pagados'    => $query->pagados(),
                default      => null,
            };
        }

        $refrendos = $query
            ->orderBy('fecha_refrendo', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Fecha límite global actual
        $fechaLimitePago = ConfigGlobal::get('fecha_limite_pago');

        return view('refrendos.index', compact('refrendos', 'fechaLimitePago'));
    }

    /**
     * Establece la fecha límite de pago global y la asigna
     * a todos los refrendos pendientes.
     */
    public function setFechaLimite(Request $request): RedirectResponse
    {
        $request->validate([
            'fecha_limite_pago' => 'required|date',
        ]);

        $fecha = $request->fecha_limite_pago;

        // 1. Guardar en config global
        ConfigGlobal::set('fecha_limite_pago', $fecha);

        // 2. Asignar a todos los refrendos pendientes
        $actualizados = Refrendo::where('estado', 'pendiente')
            ->update(['fecha_limite_pago' => $fecha]);

        return redirect()
            ->route('refrendos.index')
            ->with('success', "Fecha límite asignada a {$actualizados} refrendo(s) pendiente(s).");
    }

    /**
     * Formulario para crear refrendo manualmente
     */
    public function create(): View
    {
        return view('refrendos.create', [
            'concesiones' => Concesion::with('lote', 'titular')
                ->whereNull('deleted_at')
                ->get(),
        ]);
    }

    /**
     * Guardar refrendo manualmente
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_concesion'      => 'required|exists:concesiones,id_concesion',
            'tipo_refrendo'     => 'nullable|in:mantenimiento,administrativo',
            'monto'             => 'nullable|numeric|min:0',
            'fecha_limite_pago' => 'nullable|date',
            'observaciones'     => 'nullable|string',
        ]);

        try {
            $this->refrendoService->crear($data);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()
            ->route('refrendos.index')
            ->with('success', 'Refrendo registrado correctamente.');
    }

    /**
     * Ver detalle de refrendo
     */
    public function show(Refrendo $refrendo): View
    {
        $refrendo->load('concesion.lote', 'concesion.titular');

        return view('refrendos.show', compact('refrendo'));
    }

    /**
     * Generar siguiente refrendo anual desde la vista de una concesión
     */
    public function generarSiguiente(Request $request, Concesion $concesion): RedirectResponse
    {
        $request->validate([
            'tipo_refrendo' => 'nullable|in:mantenimiento,administrativo',
        ]);

        try {
            $this->refrendoService->generarSiguiente(
                $concesion,
                $request->tipo_refrendo ?? 'mantenimiento'
            );
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()
            ->route('concesiones.show', $concesion)
            ->with('success', 'Siguiente refrendo anual generado correctamente.');
    }

    /**
     * Devuelve datos del refrendo en JSON (para modales/JS)
     */
    public function getData(Refrendo $refrendo): \Illuminate\Http\JsonResponse
    {
        $refrendo->load('concesion.lote', 'concesion.titular', 'pago');

        return response()->json([
            'id_refrendo'       => $refrendo->id_refrendo,
            'id_concesion'      => $refrendo->id_concesion,
            'tipo_refrendo'     => $refrendo->tipo_refrendo,
            'fecha_refrendo'    => $refrendo->fecha_refrendo->format('Y-m-d'),
            'fecha_inicio'      => $refrendo->fecha_inicio->format('Y-m-d'),
            'fecha_fin'         => $refrendo->fecha_fin->format('Y-m-d'),
            'fecha_limite_pago' => $refrendo->fecha_limite_pago?->format('Y-m-d'),
            'monto'             => $refrendo->monto,
            'estado'            => $refrendo->estado,
            'estado_label'      => $refrendo->estado_label,
            'esta_vencido'      => $refrendo->esta_vencido,
            'observaciones'     => $refrendo->observaciones,
            'lote'              => $refrendo->concesion->lote->numero ?? null,
            'titular'           => $refrendo->concesion->titular->familia ?? null,
            // Datos del pago si ya existe
            'pago' => $refrendo->pago ? [
                'fecha_pago'   => $refrendo->pago->fecha_pago->format('d/m/Y'),
                'monto'        => $refrendo->pago->monto,
                'folio_ticket' => $refrendo->pago->folio_ticket,
                'forma_pago'   => $refrendo->pago->forma_pago,
            ] : null,
        ]);
    }
}