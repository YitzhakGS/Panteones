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
    <div class="card-area">
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
    document.addEventListener('DOMContentLoaded', function () {
        console.log('JS CUADRILLAS CARGADO');

        const modales = {
            edit: document.getElementById('editCuadrillaModal'),
            show: document.getElementById('showCuadrillaModal')
        };

        if (modales.edit) {
            modales.edit.addEventListener('show.bs.modal', handleEditModal);
        }

        if (modales.show) {
            modales.show.addEventListener('show.bs.modal', handleShowModal);
        }
    });

    /* ==============================
    HANDLERS (Manejadores)
    ================================ */

    function handleEditModal(event) {
        const data = getDataset(event.relatedTarget);
        fillEditModal(data);
    }

    function handleShowModal(event) {
        const data = getDataset(event.relatedTarget);
        fillShowModal(data);
    }

    /* ==============================
    HELPERS (Extracción de datos)
    ================================ */

    function getDataset(button) {
        // Aquí centralizamos la captura de datos
        return {
            id: button.dataset.id,
            nombre: button.dataset.nombre,
            idSeccion: button.dataset.id_seccion,
            seccionNombre: button.dataset.seccion,
            // Manejamos el JSON de forma segura
            espacios: button.dataset.espacios ? JSON.parse(button.dataset.espacios) : []
        };
    }

    /* ==============================
    MODAL EDIT (Llenar formulario)
    ================================ */

    function fillEditModal(data) {
        document.getElementById('edit_nombre').value = data.nombre;
        document.getElementById('edit_id_seccion').value = data.idSeccion;

        const form = document.getElementById('editCuadrillaForm');
        // Asegúrate de que el nombre del recurso sea el correcto (plural/singular)
        form.action = `/cuadrillas/${data.id}`;
    }

    /* ==============================
    MODAL SHOW (Mostrar información)
    ================================ */

    function fillShowModal(data) {
        document.getElementById('show_cuadrilla_nombre').textContent = data.nombre;
        document.getElementById('show_seccion_nombre').textContent = data.seccionNombre;

        const lista = document.getElementById('show_espacios_fisicos');
        if (lista) {
            lista.innerHTML = data.espacios.length > 0 
                ? data.espacios.map(e => `<li class="list-group-item">${e.nombre}</li>`).join('')
                : '<li class="list-group-item text-muted">Sin espacios asignados</li>';
        }
    }
    </script>
    @endpush