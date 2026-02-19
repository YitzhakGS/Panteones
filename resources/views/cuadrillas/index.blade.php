@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-0 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-collection"></i> Cuadrillas
            </h4>
        </div>
    </div>
</div>

<!-- Botón para abrir modal de creación -->
<button type="button"
        class="btn bg-base text-white mb-3"
        data-bs-toggle="modal"
        data-bs-target="#createCuadrillaModal">
    <i class="bi bi-plus-circle"></i> Nueva Cuadrilla
</button>

<div class="card p-3">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Cuadrilla</th>
                <th>Sección</th>
                <th>Espacios Físicos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($cuadrillas as $cuadrilla)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>
                    <strong>{{ $cuadrilla->nombre }}</strong>
                </td>

                <td>
                    <span class="badge bg-primary">
                        {{ $cuadrilla->seccion->nombre }}
                    </span>
                </td>

                <td>
                    <span class="badge bg-success">
                        {{ $cuadrilla->espaciosFisicos->count() }} espacios
                    </span>
                </td>

                <td>
                    <!-- SHOW -->
                    <button type="button"
                        class="btn bg-base text-white btn-show"
                        data-bs-toggle="modal"
                        data-bs-target="#showCuadrillaModal"
                        data-nombre="{{ $cuadrilla->nombre }}"
                        data-seccion="{{ $cuadrilla->seccion->nombre }}"
                        data-espacios='@json($cuadrilla->espaciosFisicos)'>
                        <i class="bi bi-eye"></i>
                    </button>

                    <!-- EDIT -->
                    <button type="button"
                            class="btn bg-base text-white btn-edit"
                            data-bs-toggle="modal"
                            data-bs-target="#editCuadrillaModal"
                            data-id="{{ $cuadrilla->id_cuadrilla }}"
                            data-nombre="{{ $cuadrilla->nombre }}"
                            data-id_seccion="{{ $cuadrilla->id_seccion }}">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <!-- DELETE -->
                    <form action="{{ route('cuadrillas.destroy', $cuadrilla->id_cuadrilla) }}"
                          method="POST"
                          style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-danger"
                                onclick="return confirm('¿Deseas eliminar esta cuadrilla?');">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-danger text-center">
                    No hay Cuadrillas registradas.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- Modales -->
@include('cuadrillas.create')
@include('cuadrillas.edit')
@include('cuadrillas.show')

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    console.log('JS SECCIONES CARGADO');

    // ========= MODALES =========
    const modales = {
        edit: document.getElementById('editSeccionModal'),
        show: document.getElementById('showSeccionModal')
    };

    // ========= INIT =========
    if (modales.edit) {
        modales.edit.addEventListener('show.bs.modal', handleEditModal);
    }

    if (modales.show) {
        modales.show.addEventListener('show.bs.modal', handleShowModal);
    }
});

/* ==============================
   HANDLERS
================================ */

function handleEditModal(event) {
    const button = event.relatedTarget;
    const data = getDataset(button);

    fillEditModal(data);
}

function handleShowModal(event) {
    const button = event.relatedTarget;
    const data = getDataset(button);

    fillShowModal(data);
}

/* ==============================
   HELPERS
================================ */

function getDataset(button) {
    return {
        id: button.dataset.id,
        nombre: button.dataset.nombre
    };
}

/* ==============================
   MODAL EDIT
================================ */

function fillEditModal(data) {
    document.getElementById('edit_nombre').value = data.nombre;

    const form = document.getElementById('editSeccionForm');
    form.action = `/secciones/${data.id}`;
}

/* ==============================
   MODAL SHOW
================================ */

function fillShowModal(data) {
    document.getElementById('show_nombre').textContent = data.nombre;
}
</script>
@endpush
