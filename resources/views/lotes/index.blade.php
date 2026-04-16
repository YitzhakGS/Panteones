@extends('layouts.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/css-view/css_tabla_lotes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-view/css_show_modal.css') }}">
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
            <button type="button" class="btn bg-base text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#createLoteModal">
                <i class="bi bi-plus-circle"></i> Nuevo Lote
            </button>
        </div>
        <div class="col-md-8">
            <form method="GET" action="{{ route('lotes.index') }}">
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control form-control-lg"
                    placeholder="Buscar por número de lote, ubicación o colindancias...">
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card-area p-3">
        <div class="cards-scroll-container">
            <table class="table lotes-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width:4%">#</th>
                        <th style="width:9%">Lote</th>
                        <th style="width:7%">m²</th>
                        <th style="width:12%">Ubicación</th>
                        <th style="width:50%">Colindancias</th>
                        <th style="width:12%">Medidas</th>
                        <th class="text-end" style="width:6%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($lotes as $lote)
                    <tr>
                        <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                        <td><div class="lote-badge">{{ $lote->numero }}</div></td>
                        <td>
                            @if($lote->metros_cuadrados)
                                <span class="m2-value">{{ number_format($lote->metros_cuadrados, 2) }}</span> <span class="m2-unit">m²</span>
                            @else
                                <span class="m2-value" style="color:#94a3b8">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="ubicacion-text">
                                <i class="bi bi-geo-alt-fill text-primary me-1"></i>
                                <span class="fw-bold">
                                    {{ $lote->espaciosActuales->first()->seccion->nombre ?? 'N/A' }}
                                </span>
                                <br>
                                <span class="text-muted">
                                    {{ $lote->espaciosActuales->first()->tipoEspacioFisico->nombre ?? 'Sin tipo' }}
                                    -
                                    {{ $lote->espaciosActuales->first()->nombre ?? 'Sin área' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="compass-grid">
                                <div class="cg-item cg-col cg-n"><span class="cg-dir">N</span><span class="cg-val">{{ $lote->col_norte ?? '--' }}</span></div>
                                <div class="cg-item cg-col cg-p"><span class="cg-dir">P</span><span class="cg-val">{{ $lote->col_poniente ?? '--' }}</span></div>
                                <div class="cg-item cg-col cg-o"><span class="cg-dir">O</span><span class="cg-val">{{ $lote->col_oriente ?? '--' }}</span></div>
                                <div class="cg-item cg-col cg-s"><span class="cg-dir">S</span><span class="cg-val">{{ $lote->col_sur ?? '--' }}</span></div>
                            </div>
                        </td>
                        <td>
                            <div class="compass-grid">
                                <div class="cg-item cg-med cg-n"><span class="cg-dir">N</span><span class="cg-val">{{ $lote->med_norte ?? '--' }}</span></div>
                                <div class="cg-item cg-med cg-p"><span class="cg-dir">P</span><span class="cg-val">{{ $lote->med_poniente ?? '--' }}</span></div>
                                <div class="cg-item cg-med cg-o"><span class="cg-dir">O</span><span class="cg-val">{{ $lote->med_oriente ?? '--' }}</span></div>
                                <div class="cg-item cg-med cg-s"><span class="cg-dir">S</span><span class="cg-val">{{ $lote->med_sur ?? '--' }}</span></div>
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="d-flex flex-column gap-2 align-items-end">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#showLoteModal" data-id="{{ $lote->id_lote }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editLoteModal" data-id="{{ $lote->id_lote }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('lotes.destroy', $lote->id_lote) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar lote?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">No hay lotes registrados.</td></tr>
                @endforelse
                </tbody>
            </table>
            @if(method_exists($lotes, 'links'))
                <div class="pagination-container d-flex justify-content-center mt-3">{{ $lotes->links() }}</div>
            @endif
        </div>
    </div>
</div>
@include('lotes.edit')
@include('lotes.create')
@include('lotes.show')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const editModal = document.getElementById('editLoteModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', handleEditLoteModal);
    }

    const showModal = document.getElementById('showLoteModal');
    if (showModal) {
        showModal.addEventListener('show.bs.modal', handleShowLoteModal);
    }

    // -------------------------
    // BUSCADOR
    // -------------------------
    document.getElementById('searchLote')?.addEventListener('keyup', function () {
        const value = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
        });
    });

    // -------------------------
    // CALCULO SUPERFICIE (EDIT)
    // -------------------------
    document.querySelectorAll('.edit-measure-input').forEach(input => {
        input.addEventListener('input', calcularSuperficieEdit);
    });

    // -------------------------
    // CAMBIO DE SECCION (EDIT)
    // 🔥 ESTE ES EL QUE TE FALTABA
    // -------------------------
    document.getElementById('edit-select-seccion-ajax')?.addEventListener('change', function () {
        const seccionId = this.value;

        cargarEspaciosFisicos(seccionId, null);
    });

});


// -------------------------
// EDIT MODAL
// -------------------------
function handleEditLoteModal(event) {
    const loteId = event.relatedTarget.dataset.id;
    const form = document.getElementById('editLoteForm');

    form.action = `/lotes/${loteId}`;

    fetch(`/lotes/${loteId}/data`)
        .then(res => res.json())
        .then(data => {

            form.querySelector('[name="numero"]').value = data.numero ?? '';
            document.getElementById('edit_metros_cuadrados').value = data.metros_cuadrados ?? '';
            document.getElementById('current_espacio_id').value = data.id_espacio_fisico ?? '';

            [
                'med_norte','med_sur','med_oriente','med_poniente',
                'col_norte','col_sur','col_oriente','col_poniente',
                'referencias'
            ].forEach(f => {
                const input = form.querySelector(`[name="${f}"]`);
                if (input) input.value = data[f] ?? '';
            });

            const selectSeccion = document.getElementById('edit-select-seccion-ajax');
            selectSeccion.value = data.id_seccion || '';

            // 🔥 cargar áreas automáticamente
            if (data.id_seccion) {
                cargarEspaciosFisicos(data.id_seccion, data.id_espacio_fisico);
            }
        });
}


// -------------------------
// SHOW MODAL
// -------------------------
function handleShowLoteModal(event) {
    const loteId = event.relatedTarget.dataset.id;

    fetch(`/lotes/${loteId}/data`)
        .then(res => res.json())
        .then(data => {

            document.getElementById('show_lote_numero').textContent      = data.numero ?? '—';
            document.getElementById('show_lote_superficie').textContent  = data.metros_cuadrados ?? '—';
            document.getElementById('show_lote_referencias').textContent = data.referencias ?? 'Sin referencias registradas...';

            document.getElementById('show_lote_ubicacion').textContent =
                data.nombre_seccion
                    ? `${data.nombre_seccion} · ${data.tipo_espacio} - ${data.nombre_espacio}`
                    : 'Sin ubicación asignada';

            ['norte','sur','oriente','poniente'].forEach(dir => {
                document.getElementById(`show_med_${dir}`).textContent = data[`med_${dir}`] ?? '—';
                document.getElementById(`show_col_${dir}`).textContent = data[`col_${dir}`] ?? '—';
            });
        });
}


// -------------------------
// CARGAR ESPACIOS
// -------------------------
function cargarEspaciosFisicos(seccionId, seleccionadoId = null, selectId = 'edit-select-espacio-ajax') {

    const select = document.getElementById(selectId);

    if (!seccionId) {
        select.innerHTML = '<option value="">Selecciona primero una sección</option>';
        return;
    }

    select.innerHTML = '<option value="">Cargando áreas...</option>';
    select.disabled = true;

    fetch(`/api/secciones/${seccionId}/espacios-fisicos`)
        .then(res => res.json())
        .then(espacios => {

            select.innerHTML = '<option value="">-- Seleccione el Área Específica --</option>';

            espacios.forEach(e => {
                const opt = document.createElement('option');
                opt.value = e.id_espacio_fisico;
                opt.textContent = `${e.tipo} - ${e.nombre}`;

                if (seleccionadoId && e.id_espacio_fisico == seleccionadoId) {
                    opt.selected = true;
                }

                select.appendChild(opt);
            });

            select.disabled = false;
        });
}


// -------------------------
// CALCULAR SUPERFICIE (EDIT)
// -------------------------
function calcularSuperficieEdit() {

    const modal = document.getElementById('editLoteModal');

    const n = parseFloat(modal.querySelector('[name="med_norte"]').value) || 0;
    const s = parseFloat(modal.querySelector('[name="med_sur"]').value) || 0;
    const o = parseFloat(modal.querySelector('[name="med_oriente"]').value) || 0;
    const p = parseFloat(modal.querySelector('[name="med_poniente"]').value) || 0;

    if ((n > 0 || s > 0) && (o > 0 || p > 0)) {

        const ancho = (n + s) / ((n > 0 && s > 0) ? 2 : 1);
        const largo = (o + p) / ((o > 0 && p > 0) ? 2 : 1);

        document.getElementById('edit_metros_cuadrados').value =
            (ancho * largo).toFixed(2);
    }
}
</script>
@endpush