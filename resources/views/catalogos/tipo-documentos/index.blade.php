@extends('layouts.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/css-view/css_tables.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
</head>

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-0 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-file-earmark-text"></i> Tipos de Documento
            </h4>
        </div>
    </div>
</div>
<button type="button" class="btn bg-base text-white mb-3 shadow-sm" data-bs-toggle="modal"
    data-bs-target="#createTipoDocumentoModal">
    <i class="bi bi-plus-circle me-1"></i> Nuevo Tipo de Documento
</button>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:5%">#</th>
                        <th style="width:25%">Nombre</th>
                        <th style="width:30%">Descripción</th>
                        <th style="width:25%">Aplica para</th>
                        <th class="text-end" style="width:20%; padding-right:15px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tiposDocumentos as $tipo)
                        <tr>
                            <td class="text-center text-muted small">
                                {{ $loop->iteration }}
                            </td>
                            <td class="fw-bold">
                                {{ $tipo->nombre }}
                            </td>
                            <td class="text-muted">
                                {{ $tipo->descripcion ?? 'Sin descripción' }}
                            </td>
                            <td>
                                @forelse ($tipo->entidades as $entidad)
                                    <span class="badge bg-secondary me-1">
                                        {{ class_basename($entidad->modelo) }}
                                    </span>
                                @empty
                                    <span class="text-muted small">Sin asignar</span>
                                @endforelse
                            </td>
                            <td class="text-end" style="padding-right:20px;">
                                <div class="btn-group shadow-sm">
                                    <button type="button" 
                                        class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editTipoDocumentoModal"
                                        data-id="{{ $tipo->id_tipo_documento }}"
                                        data-nombre="{{ $tipo->nombre }}"
                                        data-descripcion="{{ $tipo->descripcion }}"
                                        data-modelos="{{ $tipo->entidades->pluck('modelo')->toJson() }}"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('tipo-documentos.destroy', $tipo->id_tipo_documento) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Eliminar este tipo de documento?')"
                                            title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-info-circle d-block mb-2" style="font-size:2rem;"></i>
                                No hay tipos de documento registrados.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>

{{-- MODALES --}}
@include('catalogos.tipo-documentos.create')
@include('catalogos.tipo-documentos.edit')
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Inicializar TomSelect
    new TomSelect('#select-crear-modelos', {
        plugins: ['remove_button'],
        placeholder: 'Selecciona entidades...',
    });

    new TomSelect('#select-editar-modelos', {
        plugins: ['remove_button'],
        placeholder: 'Selecciona entidades...',
    });

    // Modal editar
    const editModal = document.getElementById('editTipoDocumentoModal');

    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            document.getElementById('edit_nombre').value = button.dataset.nombre;
            document.getElementById('edit_descripcion').value = button.dataset.descripcion ?? '';
            document.getElementById('editTipoDocumentoForm').action =
                `/tipo-documentos/${button.dataset.id}`;

            const modelosActivos = JSON.parse(button.dataset.modelos || '[]');
            const tsEdit = document.getElementById('select-editar-modelos').tomselect;
            tsEdit.clear();
            tsEdit.setValue(modelosActivos);
        });
    }

});
</script>
@endpush