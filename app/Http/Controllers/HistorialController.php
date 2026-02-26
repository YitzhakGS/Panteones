<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\HistorialCambio;
use App\Models\CatAccionAuditoria;
use App\Models\CatElementoAuditable;
use App\Models\User;

/**
 * Controlador encargado de la consulta del historial de cambios del sistema.
 *
 * Este controlador es exclusivamente de lectura:
 * - Lista cambios
 * - Permite filtrarlos
 * - Muestra el detalle de un evento específico
 *
 * NO contiene lógica de auditoría.
 */
class HistorialController extends Controller
{
    /**
     * Muestra el listado del historial de cambios con filtros opcionales.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Catálogos para filtros (solo lectura)
        $acciones  = CatAccionAuditoria::orderBy('nombre')->get();
        $elementos = CatElementoAuditable::orderBy('nombre')->get();
        $usuarios  = User::orderBy('name')->get();

        $historial = HistorialCambio::with([
                'accion',
                'elemento',
                'usuario'
            ])
            ->when($request->filled('entidad'), function ($query) use ($request) {
                $query->where('entidad_afectada', $request->entidad);
            })
            ->when($request->filled('id_registro'), function ($query) use ($request) {
                $query->where('id_registro', $request->id_registro);
            })
            ->when($request->filled('id_accion'), function ($query) use ($request) {
                $query->where('id_accion', $request->id_accion);
            })
            ->when($request->filled('id_elemento'), function ($query) use ($request) {
                $query->where('id_elemento', $request->id_elemento);
            })
            ->when($request->filled('id_usuario'), function ($query) use ($request) {
                $query->where('id_usuario', $request->id_usuario);
            })
            ->when($request->filled('fecha_inicio'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->fecha_inicio);
            })
            ->when($request->filled('fecha_fin'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->fecha_fin);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view(
            'historial.index',
            compact('historial', 'acciones', 'elementos', 'usuarios')
        );
    }

    /**
     * Muestra el detalle de un cambio específico del historial.
     *
     * @param HistorialCambio $historialCambio
     * @return View
     */
    public function show(HistorialCambio $historialCambio): View
    {
        $historialCambio->load([
            'accion',
            'elemento',
            'usuario'
        ]);

        return view(
            'historial.show',
            compact('historialCambio')
        );
    }

    /**
     * Devuelve la información de un evento del historial en formato JSON.
     *
     * Útil para modales o peticiones AJAX.
     *
     * @param HistorialCambio $historialCambio
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(HistorialCambio $historialCambio)
    {
        return response()->json(
            $historialCambio->load([
                'accion',
                'elemento',
                'usuario'
            ])
        );
    }
}