@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-0 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-grid"></i> Espacios Físicos
            </h4>
        </div>
    </div>
</div>

<!-- Botón crear -->
<button type="button"
        class="btn bg-base text-white mb-3"
        data-bs-toggle="modal"
        data-bs-target="#createEspacioFisicoModal">
    <i class="bi bi-plus-circle"></i> Nuevo Espacio Físico
</button>

<div class="card p-3">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Espacio</th>
                <th>Tipo</th>
                <th>Cuadrilla</th>
                <th>Sección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($espaciosFisicos as $espacio)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>
                    <strong>{{ $espacio->nombre }}</strong>
                </td>

                <td>
                    <span class="badge bg-info">
                        {{ $espacio->tipoEspacioFisico->nombre }}
                    </span>
                </td>

                <td>
                    {{ $espacio->cuadrilla->nombre }}
                </td>

                <td>
                    <span class="badge bg-primary">
                        {{ $espacio->cuadrilla->seccion->nombre }}
                    </span>
                </td>

                <td>
                    <!-- SHOW -->
                    <button type="button"
                        class="btn bg-base text-white"
                        data-bs-toggle="modal"
                        data-bs-target="#showEspacioFisicoModal"
                        data-id="{{ $espacio->id_espacio_fisico }}"
                        data-nombre="{{ $espacio->nombre }}"
                        data-descripcion="{{ $espacio->descripcion }}"
                        data-tipo="{{ $espacio->tipoEspacioFisico->nombre }}"
                        data-cuadrilla="{{ $espacio->cuadrilla->nombre }}"
                        data-seccion="{{ $espacio->cuadrilla->seccion->nombre }}">
                        <i class="bi bi-eye"></i>
                    </button>

                    <!-- EDIT -->
                    <button type="button"
                        class="btn bg-base text-white"
                        data-bs-toggle="modal"
                        data-bs-target="#editEspacioFisicoModal"
                        data-id="{{ $espacio->id_espacio_fisico }}"
                        data-nombre="{{ $espacio->nombre }}"
                        data-descripcion="{{ $espacio->descripcion }}"
                        data-id_cuadrilla="{{ $espacio->id_cuadrilla }}"
                        data-id_tipo="{{ $espacio->id_tipo_espacio_fisico }}">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <!-- DELETE -->
                    <form action="{{ route('espacios-fisicos.destroy', $espacio) }}"
                          method="POST"
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-danger"
                                onclick="return confirm('¿Eliminar este espacio físico?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-danger text-center">
                    No hay espacios físicos registrados.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- MODALES -->
@include('espacios_fisicos.create')
@include('espacios_fisicos.edit')
@include('espacios_fisicos.show')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    console.log('JS ESPACIOS FÍSICOS CARGADO');

    const modales = {
        edit: document.getElementById('editEspacioFisicoModal'),
        show: document.getElementById('showEspacioFisicoModal')
    };

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
    const data = getDataset(event.relatedTarget);
    fillEditModal(data);
}

function handleShowModal(event) {
    const data = getDataset(event.relatedTarget);
    fillShowModal(data);
}

/* ==============================
   HELPERS
================================ */

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

/* ==============================
   MODAL EDIT
================================ */

function fillEditModal(data) {
    document.getElementById('edit_nombre').value = data.nombre;
    document.getElementById('edit_descripcion').value = data.descripcion ?? '';
    document.getElementById('edit_id_cuadrilla').value = data.idCuadrilla;
    document.getElementById('edit_id_tipo_espacio_fisico').value = data.idTipo;

    const form = document.getElementById('editEspacioFisicoForm');
    form.action = `/espacios-fisicos/${data.id}`;
}

/* ==============================
   MODAL SHOW
================================ */

function fillShowModal(data) {
    document.getElementById('show_nombre').textContent = data.nombre;
    document.getElementById('show_descripcion').textContent = data.descripcion || 'Sin descripción';
    document.getElementById('show_tipo').textContent = data.tipo;
    document.getElementById('show_cuadrilla').textContent = data.cuadrilla;
    document.getElementById('show_seccion').textContent = data.seccion;
}
</script>
@endpush
