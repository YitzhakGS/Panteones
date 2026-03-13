@extends('layouts.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/css-view/css_tables.css') }}">
</head>

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-0 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-diagram-3"></i> Secciones del Cementerio
            </h4>
        </div>
    </div>
</div>

<button type="button" class="btn bg-base text-white mb-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createSeccionModal">
    <i class="bi bi-plus-circle me-1"></i> Nueva Sección
</button>

<div class="card shadow-sm border-0">
    <div class="card-body p-0"> 
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%">#</th>
                        <th class="column-highlight" style="width: 45%">Nombre de la Sección</th>
                        <th style="width: 30%">Distribución de Espacios</th>
                        <th class="text-end" style="width: 20%; padding-right: 20px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($secciones as $seccion)
                    <tr>
                        <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                        
                        <td class="column-highlight">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs me-2">
                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                        {{ substr($seccion->nombre, 0, 1) }}
                                    </span>
                                </div>
                                <span class="fw-bold text-dark">{{ $seccion->nombre }}</span>
                            </div>
                        </td>

                        <td>
                            @php
                                $totalEspacios = $seccion->espaciosFisicos->count();
                            @endphp
                            <div class="d-flex flex-column">
                                <span class="badge bg-success-subtle text-success border border-success-subtle w-fit-content">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $totalEspacios }} Espacios registrados
                                </span>
                                <small class="text-muted mt-1">
                                    Incluye: Lotes, Pasillos y Áreas Comunes
                                </small>
                            </div>
                        </td>

                        <td class="text-end" style="padding-right: 20px;">
                            
                                <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSeccionModal"
                                    data-id="{{ $seccion->id_seccion }}"
                                    data-nombre="{{ $seccion->nombre }}"
                                    title="Editar Sección">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('secciones.destroy', $seccion->id_seccion) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="btn btn-danger"
                                        onclick="return confirm('¿Deseas eliminar esta sección? Se perderá la relación con sus espacios físicos.');"
                                        title="Eliminar Sección">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
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

{{-- Incluir modales --}}
@include('catalogos.secciones.create')
@include('catalogos.secciones.edit')

@endsection

@push('scripts')
<script>
/**
 * Lógica de Secciones (Simplificada)
 */
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editSeccionModal');

    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.dataset.id;
            const nombre = button.dataset.nombre;

            // Llenar formulario
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('editSeccionForm').action = `/secciones/${id}`;
        });
    }
});
</script>
@endpush