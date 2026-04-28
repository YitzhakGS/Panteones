@extends('layouts.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/css-view/css_cards_titulares.css') }}">
    @stack('styles')
</head>

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-3 d-flex align-items-center justify-content-between"
             style="padding-bottom: 5px; margin-bottom: 0 !important;">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-person-vcard"></i> Finados
            </h4>
        </div>
    </div>
</div>

<div class="titulares-wrapper">

    {{-- Barra superior --}}
    <div class="titulares-header mb-3 row align-items-center">
        <div class="col-md-4 text-start">
            <button type="button" class="btn bg-base text-white"
                data-bs-toggle="modal" data-bs-target="#createFinadoModal">
                <i class="bi bi-plus-circle"></i> Nuevo Finado
            </button>
        </div>

        <div class="col-md-8">
            <form method="GET" action="{{ route('finados.index') }}">
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control form-control-lg"
                    placeholder="Buscar por nombre, ubicación o estado...">
            </form>
        </div>
    </div>

    {{-- Cards --}}
    <div class="card-area">
        <div class="cards-scroll-container border rounded p-2 bg-light">

            <div class="card-container">
                @forelse ($finados as $finado)

                    @php
                        $nombreCompleto = trim("{$finado->nombre} {$finado->apellido_paterno} {$finado->apellido_materno}");
                        $palabras = explode(' ', $nombreCompleto);
                        $iniciales = strtoupper(substr($palabras[0] ?? '', 0, 1) . substr($palabras[1] ?? '', 0, 1));

                        $ultimo    = $finado->ultimoMovimiento;
                        $ubicacion = $ultimo?->ubicacion_actual ?? 'Sin ubicación'; // ✅ punto y coma

                        $estado = match(optional($ultimo)->tipo) {
                            'inhumacion', 'reinhumacion', 'movimiento' => 'INHUMADO',
                            'exhumacion'                               => 'EXHUMADO',
                            default                                    => 'SIN MOVIMIENTOS'
                        };

                        $badgeColor = match($estado) {
                            'INHUMADO' => 'bg-success',
                            'EXHUMADO' => 'bg-warning text-dark',
                            default    => 'bg-secondary'
                        };
                    @endphp

                    <div class="titular-card"
                        role="button"
                        data-bs-toggle="modal"
                        data-bs-target="#showFinadoModal"
                        data-id="{{ $finado->id_finado }}"
                        data-nombre="{{ $finado->nombre }}"
                        data-apellido-paterno="{{ $finado->apellido_paterno }}"
                        data-apellido-materno="{{ $finado->apellido_materno }}"
                        data-fecha="{{ optional($finado->fecha_defuncion)->format('d/m/Y') }}"
                        data-fecha-iso="{{ optional($finado->fecha_defuncion)->format('Y-m-d') }}"
                        @php
                            $movInhumacion = $finado->movimientos
                                ->whereIn('tipo', ['inhumacion'])
                                ->sortByDesc('fecha')
                                ->first();
                        @endphp
                        data-fecha-inhumacion-iso="{{ optional($movInhumacion?->fecha)->format('Y-m-d') }}"
                        data-sexo="{{ $finado->sexo }}"
                        data-observaciones="{{ $finado->observaciones }}"
                        data-tipo-construccion="{{ $finado->tipo_construccion }}"
                        data-estado="{{ $estado }}"
                        data-concesion-id="{{ $ultimo?->id_ubicacion_actual }}"
                        data-ubicacion="{{ $ubicacion }}"
                        data-lote-id="{{ $ultimo?->ubicacionActual?->lote?->id_lote }}"
                        @php
                            $m = $finado->ultimoMovimientoOnly
                        @endphp
                        data-movimientos="{{ 
                            $m ? json_encode([[
                                'tipo' => $m->tipo,
                                'fecha' => $m->fecha ? $m->fecha->format('d/m/Y') : null,
                                'origen'  => $m->ubicacion_anterior,
                                'destino' => $m->es_externo ? $m->ubicacion_externa : $m->ubicacion_actual,
                                'externa' => $m->ubicacion_externa,
                                'solicitante' => $m->solicitante
                            ]]) : '[]'
                        }}"
                    >

                        <div class="card-avatar">
                            <div class="avatar-initials">{{ $iniciales }}</div>
                            <i class="bi bi-person avatar-icon"></i>
                        </div>

                        <div class="card-content">
                            <div class="card-row-top">
                                <span class="titular-nombre">{{ $nombreCompleto }}</span>
                                <div class="card-row-top-finados">
                                    <span class="badge {{ $badgeColor }} ms-2" style="font-size: .90rem;">
                                        {{ $estado }}
                                    </span>

                                    @if($finado->tipo_construccion)
                                        <span class="badge bg-info text-dark ms-2" style="font-size: .90rem;">
                                            {{ ucfirst($finado->tipo_construccion) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-divider"></div>

                            <div class="card-row-bottom">
                                <div class="data-chip">
                                    <i class="bi bi-calendar-event"></i>
                                    <span class="chip-text">
                                        Fallecimiento: {{ optional($finado->fecha_defuncion)->format('d/m/Y') ?? 'Sin registro' }}
                                    </span>
                                </div>
                                <span class="chip-sep">•</span>
                                <div class="data-chip">
                                    <i class="bi bi-geo-alt"></i>
                                    <span class="chip-text">Ubicación: {{ $ubicacion }}</span>
                                </div>
                                <span class="chip-sep">•</span>
                                <div class="data-chip">
                                    <i class="bi bi-gender-ambiguous"></i>
                                    <span class="chip-text">{{ $finado->sexo ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="alert alert-info text-center">
                        No hay finados registrados.
                    </div>
                @endforelse
            </div>

            @if(method_exists($finados, 'links'))
                <div class="pagination-container d-flex justify-content-center mt-3" style="padding-bottom: 40px;">
                    {{ $finados->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Modales --}}
@include('finados.create')

@if ($finados->count() > 0)
    @include('finados.show')
@endif

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // HELPERS
    // =========================

    function cargarEspaciosFisicos({ seccionId, selectId }) {
        const select = document.getElementById(selectId);

        if (!select) return;

        if (!seccionId) {
            select.innerHTML = '<option value="">Selecciona primero una sección</option>';
            return;
        }

        select.innerHTML = '<option>Cargando...</option>';
        select.disabled = true;

        fetch(`/api/secciones/${seccionId}/espacios-fisicos`)
            .then(res => res.json())
            .then(espacios => {
                select.innerHTML = '<option value="">-- Seleccione --</option>';

                espacios.forEach(e => {
                    const opt = document.createElement('option');
                    opt.value = e.id_espacio_fisico;
                    opt.textContent = `${e.tipo} - ${e.nombre}`;
                    select.appendChild(opt);
                });

                select.disabled = false;
            })
            .catch(() => {
                select.innerHTML = '<option>Error al cargar</option>';
                select.disabled = false;
            });
    }

    function calcularSuperficie(prefix) {
        const get = id => parseFloat(document.getElementById(`${prefix}_${id}`)?.value) || 0;

        const n = get('med_norte');
        const s = get('med_sur');
        const o = get('med_oriente');
        const p = get('med_poniente');

        if ((n > 0 || s > 0) && (o > 0 || p > 0)) {
            const ancho = (n + s) / ((n > 0 && s > 0) ? 2 : 1);
            const largo = (o + p) / ((o > 0 && p > 0) ? 2 : 1);

            const target = document.getElementById(`${prefix}_metros_cuadrados`);
            if (target) {
                target.value = (ancho * largo).toFixed(2);
            }
        }
    }

    function bindSubformEventos() {
        // medidas
        document.querySelectorAll('.lote-measure-input').forEach(input => {
            input.removeEventListener('input', handleSubformInput);
            input.addEventListener('input', handleSubformInput);
        });

        // sección → espacios
        const seccion = document.getElementById('lote_seccion');
        if (seccion) {
            seccion.removeEventListener('change', handleSeccionChange);
            seccion.addEventListener('change', handleSeccionChange);
        }
    }

    function handleSubformInput() {
        calcularSuperficie('lote');
    }

    function handleSeccionChange(e) {
        cargarEspaciosFisicos({
            seccionId: e.target.value,
            selectId: 'lote_espacio'
        });
    }

    // =========================
    // SHOW MODAL (finado)
    // =========================

    const showModal = document.getElementById('showFinadoModal');

    if (showModal) {
        showModal.addEventListener('show.bs.modal', function (event) {
            const card = event.relatedTarget;
            const ds = card.dataset;

            console.log(ds);

            const estado = (ds.estado || '').trim().toUpperCase();

            window.finadoActual = {
                id: ds.id,
                nombre: ds.nombre,
                apellidoPaterno: ds.apellidoPaterno,
                apellidoMaterno: ds.apellidoMaterno,
                sexo: ds.sexo,
                fecha_defuncion: ds.fecha,
                fecha_defuncion_iso: ds.fechaIso, // 👈 importante
                fecha_inhumacion_iso: ds.fechaInhumacionIso, // 👈 importante
                tipoConstruccion: ds.tipoConstruccion,
                observaciones: ds.observaciones,
                concesionId: ds.concesionId,
                loteId: ds.loteId
            };

            document.getElementById('show_sexo').textContent = ds.sexo || '—';
            document.getElementById('show_fecha_defuncion').textContent = ds.fecha || '—';
            document.getElementById('show_tipo_construccion').textContent = ds.tipoConstruccion || '—';
            document.getElementById('show_observaciones').textContent = ds.observaciones || '—';

            document.getElementById('show_id').value = ds.id;
            document.getElementById('show_nombre').textContent =
                `${ds.nombre} ${ds.apellidoPaterno ?? ''} ${ds.apellidoMaterno ?? ''}`;

            document.getElementById('show_estado').textContent = estado;
            document.getElementById('show_ubicacion').textContent = ds.ubicacion || '—';

            document.getElementById('btnMoverFinado').style.display =
                estado === 'INHUMADO' ? '' : 'none';

            const movimientos = JSON.parse(ds.movimientos || '[]');
            const container = document.getElementById('show_movimientos_container');

            container.innerHTML = '';

            if (movimientos.length === 0) {
                container.innerHTML = '<p class="text-muted">Sin movimientos registrados</p>';
                return;
            }

            movimientos.forEach(mov => {
                const div = document.createElement('div');
                div.classList.add('border', 'rounded', 'p-2', 'mb-2');

                div.innerHTML = `
                                    

                                        <!-- HEADER -->
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-primary">
                                                <i class="bi bi-arrow-left-right me-1"></i>
                                                ${mov.tipo.toUpperCase()}
                                            </span>

                                            <span class="small text-muted">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                ${mov.fecha ?? 'Sin fecha'}
                                            </span>
                                        </div>

                                        <!-- FLOW -->
                                        <div class="d-flex align-items-center justify-content-between mb-2">

                                            <div class="text-center w-45">
                                                <div class="small text-muted">Ubicación Anterior</div>
                                                <div class="fw-semibold">${mov.origen ?? '—'}</div>
                                            </div>

                                            <div class="text-center px-2">
                                                <i class="bi bi-arrow-right fs-5 text-secondary"></i>
                                            </div>

                                            <div class="text-center w-45">
                                                <div class="small text-muted">Ubicación Actual</div>
                                                <div class="fw-semibold">${mov.destino ?? '—'}</div>
                                            </div>

                                        </div>

                                        <!-- FOOTER -->
                                        <div class="small text-muted border-top pt-2">
                                            <div class="small text-muted">Solicitante</div>
                                            <i class="bi bi-person-circle me-1"></i>
                                            ${mov.solicitante ?? '—'}
                                        </div>

                                    
                                `;

                container.appendChild(div);
            });

            document.getElementById('show_sexo').textContent = ds.sexo || '—';
            document.getElementById('show_fecha_defuncion').textContent = ds.fecha || '—';
            document.getElementById('show_tipo_construccion').textContent = ds.tipoConstruccion || '—';
            document.getElementById('show_observaciones').textContent = ds.observaciones || 'Sin observaciones';
        });
    }

    // =========================
    // ABRIR MODAL MOVIMIENTO
    // =========================

    window.abrirMoverFinado = function () {

        const f = window.finadoActual || {};

        const estado = document.getElementById('show_estado').textContent.trim().toUpperCase();

        if (estado !== 'INHUMADO') {
            Swal.fire('Error', 'Solo puedes mover finados inhumados', 'error');
            return;
        }

        document.getElementById('mov_tipo').value      = 'mover';
        document.getElementById('mov_id_finado').value = f.id;
        document.getElementById('mov_concesion_actual').value = f.concesionId || '';
        document.getElementById('mov_lote_id').value = f.loteId || '';

        document.getElementById('mov_ubicacion_texto').textContent =
            document.getElementById('show_ubicacion')?.textContent || 'Sin ubicación';

        document.getElementById('mov_titulo').textContent = 'Mover finado';

        bootstrap.Modal.getInstance(document.getElementById('showFinadoModal'))?.hide();

        const modal = new bootstrap.Modal(document.getElementById('movimientoFinadoModal'));
        modal.show();
    };

    // =========================
    // MODAL MOVIMIENTO
    // =========================

    const modalMovimiento = document.getElementById('movimientoFinadoModal');

    if (modalMovimiento) {
        modalMovimiento.addEventListener('shown.bs.modal', function () {

            // activar subform
            bindSubformEventos();

            // mostrar subform directo (porque mover = interno)
            document.getElementById('subform_lote').style.display = '';
        });
    }

    // =========================
    // GUARDAR
    // =========================

    document.getElementById('mov_btn_guardar')?.addEventListener('click', async function () {

        const idFinado = document.getElementById('mov_id_finado').value;
        const fecha = document.getElementById('mov_fecha').value;
        const solicitante = document.getElementById('mov_solicitante').value;

        if (!fecha || !solicitante) {
            Swal.fire('Error', 'Faltan datos', 'error');
            return;
        }

        const id_lote = document.getElementById('mov_lote_id').value;

        if (!id_lote) {
            Swal.fire('Error', 'No hay lote', 'error');
            return;
        }

        const id_ubicacion_actual = document.getElementById('mov_concesion_actual').value;

        try {
            const res = await fetch(`/finados/${idFinado}/mover`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    id_lote,
                    fecha,
                    solicitante,
                    id_ubicacion_actual
                }),
            });

            const data = await res.json();

            if (!res.ok) {
                Swal.fire('Error', data.error ?? 'Error', 'error');
                return;
            }

            Swal.fire({
                icon: 'success',
                title: 'Movimiento registrado',
                timer: 1500,
                showConfirmButton: false
            }).then(() => window.location.reload());

        } catch {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    });

});
</script>
@endpush