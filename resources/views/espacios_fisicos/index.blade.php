@extends('layouts.app')


<head>
    <link rel="stylesheet" href="{{ asset('css/css-view/css_tables.css') }}">
</head>
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-2 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-grid"></i> Espacios Físicos
            </h4>
        </div>
    </div>
</div>

<div class="espacios-wrapper">
    {{-- Encabezado: Botón de acción --}}
    <div class="mb-3">
        <button type="button"
                class="btn bg-base text-white"
                data-bs-toggle="modal"
                data-bs-target="#createEspacioFisicoModal">
            <i class="bi bi-plus-circle"></i> Nuevo Espacio Físico
        </button>
    </div>

    {{-- Área de la Tabla --}}
    <div class="card-area" style="padding: 20px;">
        <div class="cards-scroll-container border rounded bg-white">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%">#</th>
                        <th style="width: 20%">Sección</th>
                        <th style="width: 20%">Cuadrilla</th>
                        <th class="column-highlight" style="width: 35%">Espacio Físico</th>
                        <th class="text-end" style="width: 20%; padding-right: 20px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($espaciosFisicos as $espacio)
                        <tr>
                            <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge rounded-pill border text-dark fw-normal bg-light">
                                    {{ $espacio->cuadrilla->seccion->nombre }}
                                </span>
                            </td>
                            <td>
                                <div class="badge rounded-pill border text-dark fw-normal bg-light">
                                    <i class=" me-2 text-muted"></i>
                                    <span>{{ $espacio->cuadrilla->nombre }}</span>
                                </div>
                            </td>
                            <td class="column-highlight">
                                <div class="d-flex align-items-center">
                                    <span class="espacio-tipo-inline text-truncate">
                                        <i class="bi bi-geo-alt-fill me-1 text-primary"></i>{{ $espacio->tipoEspacioFisico->nombre }}
                                    </span>
                                    <span class="mx-2 text-muted opacity-50">|</span> 
                                    <span class="espacio-nombre-inline text-truncate">{{ $espacio->nombre }}</span>
                                </div>
                            </td>
                            <td class="text-end" style="padding-right: 20px;">
                                <button type="button" class="btn btn-secondary " 
                                    data-bs-toggle="modal" data-bs-target="#editEspacioFisicoModal"
                                    data-id="{{ $espacio->id_espacio_fisico }}"
                                    data-nombre="{{ $espacio->nombre }}"
                                    data-id_cuadrilla="{{ $espacio->id_cuadrilla }}"
                                    data-id_tipo="{{ $espacio->id_tipo_espacio_fisico }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('espacios_fisicos.destroy', $espacio) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger " onclick="return confirm('¿Eliminar?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No hay espacios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                {{-- Paginación (si la tienes implementada en el controlador) --}}
                @if(method_exists($espaciosFisicos, 'links'))
                    <div class="pagination-container d-flex justify-content-center mt-3">
                        {{ $espaciosFisicos->links() }}
                    </div>
                @endif
        </div>

        
    </div>
</div>


<!-- MODALES -->
@include('espacios_fisicos.create')
@include('espacios_fisicos.edit')
@include('espacios_fisicos.show')

@endsection
@push('scripts')
<script>
/**
 * Gestión del Módulo de Espacios Físicos
 * Maneja la lógica de visualización, edición y navegación (scroll) 
 * para la administración de ubicaciones físicas.
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('JS ESPACIOS FÍSICOS CARGADO');

    // Referencias a los contenedores modales de Bootstrap
    const modales = {
        edit: document.getElementById('editEspacioFisicoModal'),
        show: document.getElementById('showEspacioFisicoModal')
    };

    // Inicialización de escuchadores para modales
    if (modales.edit) {
        modales.edit.addEventListener('show.bs.modal', handleEditModal);
    }

    if (modales.show) {
        modales.show.addEventListener('show.bs.modal', handleShowModal);
    }

    /**
     * Mejora de UX para la paginación:
     * Al hacer clic en cualquier enlace de la paginación, el contenedor de tarjetas
     * vuelve a la parte superior con una animación suave.
     */
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', () => {
            document.querySelector('.cards-scroll-container')
                ?.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
});

/* ==========================================================================
   HANDLERS (Manejadores de Eventos)
   ========================================================================== */

function handleEditModal(event) {
    // relatedTarget obtiene el botón exacto que disparó el evento
    const data = getDataset(event.relatedTarget);
    fillEditModal(data);
}

function handleShowModal(event) {
    const data = getDataset(event.relatedTarget);
    fillShowModal(data);
}

/* ==========================================================================
   HELPERS (Extracción de Atributos)
   ========================================================================== */

/**
 * Mapea los atributos 'data-' del HTML a un objeto literal de JavaScript.
 * @param {HTMLElement} button - El disparador (trigger) del modal.
 * @returns {Object} Diccionario con la información del Espacio Físico.
 */
function getDataset(button) {
    return {
        id: button.dataset.id,
        nombre: button.dataset.nombre,
        descripcion: button.dataset.descripcion,
        tipo: button.dataset.tipo,
        cuadrilla: button.dataset.cuadrilla,
        seccion: button.dataset.seccion,
        idCuadrilla: button.dataset.id_cuadrilla,
        idTipo: button.dataset.id_tipo
    };
}

/* ==========================================================================
   MODAL EDIT (Preparación de Formulario)
   ========================================================================== */

/**
 * Asigna los valores actuales al formulario de edición.
 * @param {Object} data - Objeto con los datos del registro.
 */
function fillEditModal(data) {
    document.getElementById('edit_nombre').value = data.nombre;
    // Operador Nullish coalescing (??) para manejar descripciones vacías de forma segura
    document.getElementById('edit_descripcion').value = data.descripcion ?? '';
    document.getElementById('edit_id_cuadrilla').value = data.idCuadrilla;
    document.getElementById('edit_id_tipo_espacio_fisico').value = data.idTipo;

    // Actualiza la ruta del formulario para el método PUT/PATCH de Laravel
    const form = document.getElementById('editEspacioFisicoForm');
    form.action = `/espacios_fisicos/${data.id}`;
}

/* ==========================================================================
   MODAL SHOW (Carga de Información)
   ========================================================================== */

/**
 * Inyecta el texto del registro seleccionado en los elementos de visualización.
 * @param {Object} data - Objeto con los datos del registro.
 */
function fillShowModal(data) {
    document.getElementById('show_nombre').textContent = data.nombre;
    document.getElementById('show_descripcion').textContent = data.descripcion || 'Sin descripción';
    document.getElementById('show_tipo').textContent = data.tipo;
    document.getElementById('show_cuadrilla').textContent = data.cuadrilla;
    document.getElementById('show_seccion').textContent = data.seccion;
}
</script>
@endpush