@extends('layouts.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/css-view/css_tables.css') }}">
</head>

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-0 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-diagram-3"></i> Secciones
            </h4>
        </div>
    </div>
</div>

<!-- Botón para abrir modal de creación -->
<button type="button" class="btn bg-base text-white mb-3" data-bs-toggle="modal" data-bs-target="#createSeccionModal">
    <i class="bi bi-plus-circle"></i> Nueva Sección
</button>

<div class="card shadow-sm border-0">
    <div class="card-body p-0"> <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%">#</th>
                        <th class="column-highlight" style="width: 25%">Sección</th>
                        <th style="width: 30%">Cuadrillas Relacionadas</th>
                        <th style="width: 20%">Resumen Espacios</th>
                        <th class="text-end" style="width: 20%; padding-right: 20px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($secciones as $seccion)
                    <tr>
                        <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                        
                        <td class="column-highlight">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-diagram-3 text-primary me-2"></i>
                                <span>{{ $seccion->nombre }}</span>
                            </div>
                        </td>

                        <td>
                            @forelse ($seccion->cuadrillas as $cuadrilla)
                                <span class="badge rounded-pill border text-dark fw-normal bg-light">
                                    {{ $cuadrilla->nombre }}
                                </span>
                            @empty
                                <span class="text-muted small italic">Sin cuadrillas</span>
                            @endforelse
                        </td>

                        <td>
                            @php
                                $totalEspacios = $seccion->cuadrillas->sum(fn($c) => $c->espaciosFisicos->count());
                            @endphp
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-geo-alt me-1"></i>{{ $totalEspacios }} espacios
                            </span>
                        </td>

                        <td class="text-end" style="padding-right: 20px;">
                                <button type="button"
                                    class="btn  btn-secondary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSeccionModal"
                                    data-id="{{ $seccion->id_seccion }}"
                                    data-nombre="{{ $seccion->nombre }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('secciones.destroy', $seccion->id_seccion) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="btn btn-danger"
                                        onclick="return confirm('¿Deseas eliminar esta sección?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-info-circle d-block mb-2" style="font-size: 2rem;"></i>
                            No hay Secciones registradas actualmente.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Incluir modales -->
@include('secciones.create')
@include('secciones.edit')
@include('secciones.show')

@endsection

@push('scripts')
<script>
/**
 * Gestión del Módulo de Secciones
 * Script encargado de la lógica para visualizar y editar las secciones
 * administrativas del sistema mediante modales de Bootstrap.
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('JS DE SECCIONES UNIFICADO Y CARGADO');

    /** * @description Objeto que centraliza las referencias a los modales del DOM.
     */
    const modales = {
        edit: document.getElementById('editSeccionModal'),
        show: document.getElementById('showSeccionModal')
    };

    // Inicialización de escuchadores de eventos para los modales
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
 * Procesa la apertura del modal de edición.
 * @param {Event} event - Evento disparado por Bootstrap.
 */
function handleEditModal(event) {
    const data = getDataset(event.relatedTarget);
    fillEditModal(data);
}

/**
 * Procesa la apertura del modal de visualización.
 * @param {Event} event - Evento disparado por Bootstrap.
 */
function handleShowModal(event) {
    const data = getDataset(event.relatedTarget);
    fillShowModal(data);
}

/* ==========================================================================
   HELPERS (Extracción de Datos)
   ========================================================================== */

/**
 * Extrae la información de la sección desde los atributos 'data-' del botón.
 * @param {HTMLElement} button - El botón que activó el modal.
 * @returns {Object} Objeto con id y nombre de la sección.
 */
function getDataset(button) {
    return {
        id: button.dataset.id,
        nombre: button.dataset.nombre
    };
}

/* ==========================================================================
   MODAL EDIT (Llenado de Formulario)
   ========================================================================== */

/**
 * Prepara el formulario de edición con los datos de la sección seleccionada.
 * @param {Object} data - Datos obtenidos vía getDataset.
 */
function fillEditModal(data) {
    // Asigna el nombre actual al input de edición
    document.getElementById('edit_nombre').value = data.nombre;

    // Actualiza la URL de destino del formulario (endpoint de actualización)
    const form = document.getElementById('editSeccionForm');
    form.action = `/secciones/${data.id}`;
}

/* ==========================================================================
   MODAL SHOW (Visualización de Información)
   ========================================================================== */

/**
 * Muestra el nombre de la sección en el modal de detalles.
 * @param {Object} data - Datos obtenidos vía getDataset.
 */
function fillShowModal(data) {
    document.getElementById('show_nombre').textContent = data.nombre;
}
</script>
@endpush