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
                        <th style="width: 25%">Sección</th> <th class="column-highlight" style="width: 45%">Espacio Físico</th> <th class="text-end" style="width: 25%; padding-right: 20px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($espaciosFisicos as $espacio)
                        <tr>
                            <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge rounded-pill border text-dark fw-normal bg-light">
                                    <i class="bi bi-geo me-1"></i> {{ $espacio->seccion->nombre }}
                                </span>
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
                                <button type="button" class="btn btn-secondary" 
                                    data-bs-toggle="modal" data-bs-target="#editEspacioFisicoModal"
                                    data-id="{{ $espacio->id_espacio_fisico }}"
                                    data-nombre="{{ $espacio->nombre }}"
                                    data-descripcion="{{ $espacio->descripcion }}"
                                    data-id_seccion="{{ $espacio->id_seccion }}"
                                    data-id_tipo="{{ $espacio->id_tipo_espacio_fisico }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('espacios_fisicos.destroy', $espacio) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar este espacio?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No hay espacios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if(method_exists($espaciosFisicos, 'links'))
                <div class="pagination-container d-flex justify-content-center mt-3">
                    {{ $espaciosFisicos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@include('catalogos.espacios_fisicos.create')
@include('catalogos.espacios_fisicos.edit')
{{-- @include('catalogos.espacios_fisicos.show') --}} 
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEdit = document.getElementById('editEspacioFisicoModal');

    if (modalEdit) {
        modalEdit.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            // Extraer info de los atributos data-
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const descripcion = button.getAttribute('data-descripcion');
            const idSeccion = button.getAttribute('data-id_seccion');
            const idTipo = button.getAttribute('data-id_tipo');

            // Rellenar el formulario
            document.getElementById('edit_id_espacio_fisico').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_descripcion').value = descripcion === 'null' ? '' : descripcion;
            document.getElementById('edit_id_seccion').value = idSeccion;
            document.getElementById('edit_id_tipo_espacio_fisico').value = idTipo;

            // Actualizar Action del Form
            const form = document.getElementById('editEspacioFisicoForm');
            form.action = `/espacios_fisicos/${id}`;
        });
    }

    // Scroll suave en paginación
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', () => {
            document.querySelector('.cards-scroll-container')?.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
});
</script>
@endpush