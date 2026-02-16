@extends('layouts.app')

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

<div class="card p-3">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Sección</th>
                <th>Cuadrillas</th>
                <th>Espacios Físicos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($secciones as $seccion)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $seccion->nombre }}</strong></td>
                <td>
                    @forelse ($seccion->cuadrillas as $cuadrilla)
                        <span class="badge bg-primary">{{ $cuadrilla->nombre }}</span>
                    @empty
                        <span class="text-muted">Sin cuadrillas</span>
                    @endforelse
                </td>
                <td>
                    @php
                        $totalEspacios = $seccion->cuadrillas->sum(fn($c) => $c->espaciosFisicos->count());
                    @endphp
                    <span class="badge bg-success">{{ $totalEspacios }} espacios</span>
                </td>
                <td>
                    <!-- Botones que abren modales -->
                    <button type="button" 
                            class="btn bg-base text-white btn-show"
                            data-bs-toggle="modal"
                            data-bs-target="#showSeccionModal" 
                            data-id="{{ $seccion->id_seccion }}"
                            data-nombre="{{  $seccion->nombre }}">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button type="button"
                            class="btn bg-base text-white btn-edit"
                            data-bs-toggle="modal"
                            data-bs-target="#editSeccionModal"
                            data-id="{{ $seccion->id_seccion }}"
                            data-nombre="{{  $seccion->nombre }}"> 
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <form action="{{ route('secciones.destroy', $seccion->id_seccion) }}" method="POST" style="display: inline;">
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
            <tr><td colspan="5" class="text-danger text-center">No hay Secciones registradas.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- Incluir modales -->
@include('secciones.create')
@include('secciones.edit')
@include('secciones.show')

@endsection
@push('scripts')
<script>
    console.log('JS DE SECCIONES CARGADO');
    
    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editSeccionModal');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        var id_secciones = button.getAttribute('data-id');
        var nombre_update = button.getAttribute('data-nombre');

        // Inputs del modal
        document.getElementById('edit_nombre').value = nombre_update;

        // Action del formulario
        const form = document.getElementById('editSeccionForm');
        form.action = `/secciones/${id_secciones}`;
    });

});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
        console.log('JS DE SECCIONES CARGADO');
        const modal = document.getElementById('showSeccionModal');
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            console.log('Botón que abrió el modal:', button);

            var id_secciones = button.getAttribute('data-id');
            var nombre_show = button.getAttribute('data-nombre');
            console.log('ID de sección:', id_secciones);
            console.log('Nombre de sección:', nombre_show);

            // Inputs del modal
            document.getElementById('show_nombre').textContent = nombre_show;
        });

    })
</script>
@endpush