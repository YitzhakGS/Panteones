@extends('layouts.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/css-view/css_tables.css') }}">
</head>

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-2 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-collection"></i> Cuadrillas
            </h4>
        </div>
    </div>
</div>

{{-- Envolvemos todo en el wrapper para controlar la altura total --}}
<div class="espacios-wrapper">
    
    {{-- Sección de botones (Header de la vista) --}}
    <div class="mb-3">
        <button type="button" class="btn bg-base text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#createCuadrillaModal">
            <i class="bi bi-plus-circle"></i> Nueva Cuadrilla
        </button>
    </div>

    {{-- Área de la Tabla con scroll --}}
    <div class="card-area" style="padding: 20px;">
        <div class="cards-scroll-container border rounded bg-white">
            <table class="table table-hover align-middle mb-0">
                {{-- La clase sticky-top ahora funcionará gracias al CSS que preparamos --}}
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%">#</th>
                        <th style="width: 20%">Sección</th>
                        <th class="column-highlight" style="width: 25%">Cuadrilla</th>
                        <th style="width: 30%">Espacios Físicos</th>
                        <th class="text-end" style="width: 20%; padding-right: 20px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($cuadrillas as $cuadrilla)
                    <tr>
                        <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                        
                        <td>
                            <span class="badge rounded-pill border text-dark fw-normal bg-light">
                                {{ $cuadrilla->seccion->nombre }}
                            </span>
                        </td>

                        <td class="column-highlight">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-columns-gap me-2 text-primary"></i>
                                <span class="fw-bold">{{ $cuadrilla->nombre }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @forelse ($cuadrilla->espaciosFisicos as $espacio)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle " style="margin-bottom: 5px;>
                                        <small class="d-block" style="font-size: 0.6rem; text-transform: uppercase; opacity: 0.8;">
                                            {{ $espacio->tipoEspacioFisico->nombre }}
                                        </small>
                                        {{ $espacio->nombre }}
                                    </span>
                                @empty
                                    <span class="text-muted small italic">Sin espacios asignados</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="text-end" style="padding-right: 20px;">
                            <button type="button" class="btn btn-secondary "
                                data-bs-toggle="modal" data-bs-target="#editCuadrillaModal"
                                data-id="{{ $cuadrilla->id_cuadrilla }}"
                                data-nombre="{{ $cuadrilla->nombre }}"
                                data-id_seccion="{{ $cuadrilla->id_seccion }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <form action="{{ route('cuadrillas.destroy', $cuadrilla->id_cuadrilla) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger " onclick="return confirm('¿Eliminar?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No hay Cuadrillas registradas.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>


    </div>
</div>

@include('cuadrillas.create')
@include('cuadrillas.edit')
@include('cuadrillas.show')

@endsection
@push('scripts')
<script>
/**
 * Gestión de Modales para el Módulo de Cuadrillas
 * Este script maneja la inicialización, extracción de datos y llenado dinámico
 * de los modales de Edición y Visualización utilizando Bootstrap 5.
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('JS CUADRILLAS CARGADO');

    // Referencias a los elementos del DOM de los modales
    const modales = {
        edit: document.getElementById('editCuadrillaModal'),
        show: document.getElementById('showCuadrillaModal')
    };

    // Asignación de eventos 'show.bs.modal' (se dispara justo antes de mostrarse)
    if (modales.edit) {
        modales.edit.addEventListener('show.bs.modal', handleEditModal);
    }

    if (modales.show) {
        modales.show.addEventListener('show.bs.modal', handleShowModal);
    }
});

/* ==========================================================================
   HANDLERS (Manejadores de Eventos)
   ========================================================================== */

/**
 * Gestiona la lógica cuando se abre el modal de edición.
 * @param {Event} event - Evento nativo de Bootstrap.
 */
function handleEditModal(event) {
    // relatedTarget es el botón que activó el modal
    const data = getDataset(event.relatedTarget);
    fillEditModal(data);
}

/**
 * Gestiona la lógica cuando se abre el modal de visualización.
 * @param {Event} event - Evento nativo de Bootstrap.
 */
function handleShowModal(event) {
    const data = getDataset(event.relatedTarget);
    fillShowModal(data);
}

/* ==========================================================================
   HELPERS (Utilidades de Extracción)
   ========================================================================== */

/**
 * Centraliza la extracción de atributos 'data-' del botón disparador.
 * @param {HTMLElement} button - El botón que contiene los atributos data.
 * @returns {Object} Objeto con la información de la cuadrilla formateada.
 */
function getDataset(button) {
    return {
        id: button.dataset.id,
        nombre: button.dataset.nombre,
        idSeccion: button.dataset.id_seccion,
        seccionNombre: button.dataset.seccion,
        // Intenta parsear el JSON de espacios; si falla o no existe, devuelve array vacío
        espacios: button.dataset.espacios ? JSON.parse(button.dataset.espacios) : []
    };
}

/* ==========================================================================
   MODAL EDIT (Llenado de Formulario)
   ========================================================================== */

/**
 * Puebla los inputs del formulario de edición y actualiza la URL del action.
 * @param {Object} data - Datos obtenidos desde getDataset.
 */
function fillEditModal(data) {
    document.getElementById('edit_nombre').value = data.nombre;
    document.getElementById('edit_id_seccion').value = data.idSeccion;

    const form = document.getElementById('editCuadrillaForm');
    // Actualiza dinámicamente el endpoint para el envío (Laravel RESTful)
    form.action = `/cuadrillas/${data.id}`;
}

/* ==========================================================================
   MODAL SHOW (Visualización de Información)
   ========================================================================== */

/**
 * Renderiza la información de la cuadrilla en el modal de vista previa.
 * @param {Object} data - Datos obtenidos desde getDataset.
 */
function fillShowModal(data) {
    document.getElementById('show_cuadrilla_nombre').textContent = data.nombre;
    document.getElementById('show_seccion_nombre').textContent = data.seccionNombre;

    const lista = document.getElementById('show_espacios_fisicos');
    if (lista) {
        // Genera una lista <li> por cada espacio físico o muestra un mensaje de vacío
        lista.innerHTML = data.espacios.length > 0 
            ? data.espacios.map(e => `<li class="list-group-item">${e.nombre}</li>`).join('')
            : '<li class="list-group-item text-muted">Sin espacios asignados</li>';
    }
}
</script>
@endpush