@extends('layouts.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/css-view/css_tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-view/css_show_modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-view/css_cards.css') }}">
</head>

@section('content')

{{-- Título --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-2 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-grid-3x3-gap"></i> Lotes
            </h4>
        </div>
    </div>
</div>

<div class="espacios-wrapper">

    {{-- Acciones --}}
    
    <div class="mb-3 row align-items-center" style="padding-top:10px; padding-left:10px; padding-right:10px;">
        <div class="col-md-4 text-start">
            <button type="button"
                    class="btn bg-base text-white shadow-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#createLoteModal">
                <i class="bi bi-plus-circle"></i> Nuevo Lote
            </button>
        </div>
        
        <div class="col-md-8">
            <input type="text" id="searchLote" class="form-control form-control-lg"
                placeholder="Buscar por número de lote, ubicacion o colindancias...">
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card-area p-3">
        <div class="cards-scroll-container border rounded bg-white">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light sticky-top bg-white">
                    <tr>
                        <th class="text-center" style="width:5%">#</th>
                        <th style="width:10%">Lote</th>
                        <th style="width:10%">m²</th>
                        <th style="width:15%">Ubicación</th>
                        <th style="width:40%">Colindancias</th>
                        <th style="width:12%">Medidas (m)</th>
                        <th class="text-end" style="width:8%">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($lotes as $lote)
                    <tr>
                        {{-- # --}}
                        <td class="text-center text-muted small">
                            {{ $loop->iteration }}
                        </td>

                        {{-- Lote --}}
                        <td>
                            <span class="badge bg-primary text-white" style="font-size:1.2rem;">
                                {{ $lote->numero }}
                            </span>
                        </td>

                        {{-- Metros cuadrados --}}
                        <td>
                            <span class="fw-semibold" style="font-size:1.2rem;">
                                {{ number_format($lote->metros_cuadrados, 2) }}
                            </span>
                            <small class="text-muted" style="font-size:1.1rem;">m²</small>
                        </td>

                        {{-- Ubicación --}}
                        <td>
                            @if ($lote->ubicacion_formateada)
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-pin-map-fill me-2 text-success"></i>
                                    <span>{!! nl2br(e($lote->ubicacion_formateada)) !!}</span>
                                </div>
                            @else
                                <span class="text-muted small">Sin ubicación asignada</span>
                            @endif
                        </td>

                        {{-- Colindancias --}}
                        <td>
                           <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-light text-dark border fs-6 p-2 w-100 text-start">
                                    N: {{ $lote->col_norte ?? '--' }}
                                </span>
                                <span class="badge bg-light text-dark border fs-6 p-2 w-100 text-start">
                                    S: {{ $lote->col_sur ?? '--' }}
                                </span>
                                <span class="badge bg-light text-dark border fs-6 p-2 w-100 text-start">
                                    O: {{ $lote->col_oriente ?? '--' }}
                                </span>
                                <span class="badge bg-light text-dark border fs-6 p-2 w-100 text-start">
                                    P: {{ $lote->col_poniente ?? '--' }}
                                </span>
                            </div>
                        </td>

                        {{-- Medidas --}}
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-info-subtle text-info-emphasis border fs-6 p-2 w-100">
                                    N: {{ $lote->med_norte ?? '--' }}
                                </span>
                                <span class="badge bg-info-subtle text-info-emphasis border fs-6 p-2 w-100">
                                    S: {{ $lote->med_sur ?? '--' }}
                                </span>
                                <span class="badge bg-info-subtle text-info-emphasis border fs-6 p-2 w-100">
                                    O: {{ $lote->med_oriente ?? '--' }}
                                </span>
                                <span class="badge bg-info-subtle text-info-emphasis border fs-6 p-2 w-100">
                                    P: {{ $lote->med_poniente ?? '--' }}
                                </span>
                            </div>
                        </td>

                        <td class="text-end" style="padding-right: 20px;">
                            <div class="d-flex flex-column gap-2 align-items-end">

                                {{-- Ver --}}
                                <button type="button"
                                        class="btn btn-secondary"
                                        title="Ver"
                                        data-bs-toggle="modal"
                                        data-bs-target="#showLoteModal"
                                        data-id="{{ $lote->id_lote }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                {{-- Editar --}}
                                <button type="button"
                                        class="btn btn-secondary"
                                        title="Editar"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editLoteModal"
                                        data-id="{{ $lote->id_lote }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                {{-- Eliminar --}}
                                <form action="{{ route('lotes.destroy', $lote->id_lote) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger"
                                            title="Eliminar"
                                            onclick="return confirm('¿Eliminar lote?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            No hay lotes registrados.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
            {{-- Paginación --}}
            @if(method_exists($lotes, 'links'))
                <div class="pagination-container d-flex justify-content-center mt-3">
                    {{ $lotes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modales --}}
@include('lotes.create')
{{-- Modal editar (solo si hay lotes) --}}
@if ($lotes->count() > 0)
    @include('lotes.edit')
@endif


@include('lotes.show')

@endsection

@push('scripts')
<script>
/**
 * Gestión del Módulo de Lotes
 * Maneja el filtrado de tablas, scroll dinámico y la carga de datos vía AJAX
 * para la visualización y edición de lotes, incluyendo carga de áreas dependientes.
 */

/**
 * Filtro de búsqueda para la tabla de lotes.
 */
document.getElementById('searchLote').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();

    document.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(value)
            ? ''
            : 'none';
    });
});

/**
 * Control de scroll suave para la paginación.
 */
document.querySelectorAll('.pagination a').forEach(link => {
    link.addEventListener('click', () => {
        document.querySelector('.cards-scroll-container')
            ?.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

/* ==========================================================================
   INICIALIZACIÓN DE MODALES (SHOW / EDIT)
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {
    const showModal = document.getElementById('showLoteModal');
    const editModal = document.getElementById('editLoteModal');

    if (showModal) {
        showModal.addEventListener('show.bs.modal', handleShowLoteModal);
    }
    if (editModal) {
        editModal.addEventListener('show.bs.modal', handleEditLoteModal);
    }
});

/* ==========================================================================
   MANEJADORES DE DATOS (AJAX FETCH)
   ========================================================================== */

/**
 * Obtiene los datos de un lote desde el servidor para el modal de visualización.
 * @param {Event} event - Evento show.bs.modal de Bootstrap.
 */
function handleShowLoteModal(event) {
    const button = event.relatedTarget;
    const loteId = button.dataset.id;

    fetch(`/lotes/${loteId}/data`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => fillShowLoteModal(data))
    .catch(() => alert('Error al cargar la información del lote'));
}

/**
 * Obtiene los datos de un lote y gestiona la carga de selects para edición.
 * @param {Event} event - Evento show.bs.modal de Bootstrap.
 */
function handleEditLoteModal(event) {
    const button = event.relatedTarget;
    const loteId = button.dataset.id;

    fetch(`/lotes/${loteId}/data`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => fillEditLoteModal(data))
    .catch(() => alert('Error al cargar la información del lote'));
}

/* ==========================================================================
   LLENADO DE CAMPOS (DOM MANIPULATION)
   ========================================================================== */

/**
 * Inserta los datos del lote en los elementos de texto del modal de visualización.
 * @param {Object} data - Objeto con la información completa del lote.
 */
function fillShowLoteModal(data) {
    document.getElementById('show_lote_numero').textContent = data.numero;
    document.getElementById('show_lote_superficie').textContent = data.metros_cuadrados;

    // Asignación con operador de nulidad para colindancias
    const fields = ['med_norte', 'med_sur', 'med_oriente', 'med_poniente', 
                    'col_norte', 'col_sur', 'col_oriente', 'col_poniente'];
    
    fields.forEach(field => {
        document.getElementById(`show_${field}`).textContent = data[field] ?? '—';
    });

    document.getElementById('show_lote_referencias').textContent = data.referencias || 'Sin referencias';
    document.getElementById('show_lote_ubicacion').textContent = data.ubicacion;
}

/**
 * Puebla el formulario de edición y gestiona la carga asíncrona de espacios físicos.
 * @param {Object} data - Objeto con la información del lote y sus relaciones.
 */
function fillEditLoteModal(data) {
    const modal = document.getElementById('editLoteModal');
    const form = document.getElementById('editLoteForm');

    form.action = `/lotes/${data.id_lote}`;

    /**
     * Helper interno para asignar valores a inputs por su atributo name.
     * @param {string} name - Atributo name del input.
     * @param {any} val - Valor a asignar.
     */
    const setVal = (name, val) => {
        const input = modal.querySelector(`[name="${name}"]`);
        if (input) input.value = val ?? '';
    };

    // Llenado de campos básicos
    setVal('numero', data.numero);
    document.getElementById('edit_metros_cuadrados').value = data.metros_cuadrados ?? '';
    
    ['med_norte', 'med_sur', 'med_oriente', 'med_poniente', 
     'col_norte', 'col_sur', 'col_oriente', 'col_poniente', 'referencias'].forEach(f => setVal(f, data[f]));

    // Lógica de Selects Dependientes (Cuadrilla -> Espacio)
    const selectCuadrilla = document.getElementById('edit-select-cuadrilla-ajax');
    const selectEspacio = document.getElementById('edit-select-espacio-ajax');

    selectCuadrilla.value = data.id_cuadrilla || '';
    
    if (data.id_cuadrilla) {
        selectEspacio.innerHTML = '<option value="">Cargando áreas...</option>';
        
        // Petición AJAX para obtener espacios basados en la cuadrilla seleccionada
        fetch(`/api/cuadrillas/${data.id_cuadrilla}/espacios`)
            .then(response => response.json())
            .then(espacios => {
                selectEspacio.innerHTML = '<option value="">-- Seleccione el Área Específica --</option>';
                espacios.forEach(espacio => {
                    const option = document.createElement('option');
                    option.value = espacio.id;
                    option.textContent = `${espacio.tipo} ${espacio.nombre}`;
                    // Mantiene la selección actual si coincide con el dato del lote
                    if (espacio.id == data.id_espacio_fisico) option.selected = true;
                    selectEspacio.appendChild(option);
                });
                selectEspacio.disabled = false;
            });
    }
}

</script>
@endpush