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

                        $ultimo = $finado->ultimoMovimiento;
                        $espacio = $ultimo?->concesion?->lote?->espacioActual;

                        $estado = match(optional($ultimo)->tipo) {
                            'inhumacion', 'reinhumacion' => 'INHUMADO',
                            'exhumacion' => 'EXHUMADO',
                            default => 'SIN MOVIMIENTOS'
                        };

                        $badgeColor = match($estado) {
                            'INHUMADO' => 'bg-success',
                            'EXHUMADO' => 'bg-warning text-dark',
                            default => 'bg-secondary'
                        };

                        $ubicacion = $espacio
                            ? trim(
                                optional($espacio->seccion)->nombre . ', ' .
                                optional($espacio->tipoEspacioFisico)->nombre . ' ' .
                                $espacio->nombre . ', Lote ' .
                                $ultimo->concesion->lote->numero
                            )
                            : 'Sin ubicación';
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
                        data-sexo="{{ $finado->sexo }}"
                        data-observaciones="{{ $finado->observaciones }}"
                        data-tipo-construccion="{{ $finado->tipo_construccion }}"
                        data-estado="{{ $estado }}"
                        data-ubicacion="{{ $ubicacion }}"
                        data-movimientos="{{ 
                            $finado->movimientos->map(fn($m) => [
                                'tipo' => $m->tipo,
                                'fecha' => $m->fecha ? $m->fecha->format('d/m/Y') : null,
                                'origen' => $m->concesion?->lote?->seccion?->nombre ?? null,
                                'destino' => $m->concesion?->lote?->seccion?->nombre ?? null,
                                'externa' => $m->ubicacion_destino_externa,
                                'solicitante' => $m->solicitante
                            ])->toJson()
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
                <div class="pagination-container d-flex justify-content-center mt-3">
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

    const showModal = document.getElementById('showFinadoModal');

    if (showModal) {
        showModal.addEventListener('show.bs.modal', function (event) {
            const card = event.relatedTarget;
            const ds = card.dataset; // Shortcut para los datos de la card

            // 1. Guardar estado global para el modal Edit
            window.finadoActual = {
                id: ds.id,
                nombre: ds.nombre,
                apellidoPaterno: ds.apellidoPaterno,
                apellidoMaterno: ds.apellidoMaterno,
                sexo: ds.sexo,
                fecha_defuncion: ds.fecha,
                fecha_defuncion_iso: ds.fechaIso,
                tipoConstruccion: ds.tipoConstruccion,
                observaciones: ds.observaciones
            };

            // 2. Llenar campos del Modal Show con validaciones
            document.getElementById('show_id').value = ds.id;
            
            document.getElementById('show_nombre').textContent = 
                `${ds.nombre} ${ds.apellidoPaterno ?? ''} ${ds.apellidoMaterno ?? ''}`;

            document.getElementById('show_sexo').textContent = ds.sexo || 'No especificado';

            // Corrección Fecha de Defunción
            const fechaTxt = document.getElementById('show_fecha_defuncion');
            fechaTxt.textContent = (ds.fecha && ds.fecha !== '—') ? ds.fecha : 'Sin registro';
            fechaTxt.className = (ds.fecha && ds.fecha !== '—') ? 'fw-semibold mb-0' : 'text-muted mb-0 small';

            // Corrección Tipo de Construcción
            const constTxt = document.getElementById('show_tipo_construccion');
            constTxt.textContent = ds.tipoConstruccion 
                ? ds.tipoConstruccion.charAt(0).toUpperCase() + ds.tipoConstruccion.slice(1) 
                : 'No tiene';
            constTxt.className = ds.tipoConstruccion ? 'fw-semibold mb-0' : 'text-muted mb-0 small';

            document.getElementById('show_estado').textContent = ds.estado || '—';
            document.getElementById('show_ubicacion').textContent = ds.ubicacion || '—';

            // Observaciones
            const obsTxt = document.getElementById('show_observaciones');
            obsTxt.textContent = (ds.observaciones && ds.observaciones !== 'null') 
                ? ds.observaciones 
                : 'Sin observaciones adicionales';
            obsTxt.className = (ds.observaciones && ds.observaciones !== 'null') ? 'fw-semibold mb-0' : 'text-muted mb-0 small';

            // 3. Historial de movimientos
            const movimientos = JSON.parse(ds.movimientos || '[]');
            const container = document.getElementById('show_movimientos_container');
            container.innerHTML = '';

            if (movimientos.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="bi bi-clock-history fs-2 text-muted"></i>
                        <p class="text-muted mb-0 mt-2">Sin movimientos registrados</p>
                    </div>
                `;
            } else {
                movimientos.forEach(m => {
                    let badge = (m.tipo === 'inhumacion') ? 'bg-success' : 
                                (m.tipo === 'exhumacion') ? 'bg-warning text-dark' : 
                                (m.tipo === 'reinhumacion') ? 'bg-info text-dark' : 'bg-secondary';

                    container.innerHTML += `
                        <div class="card mb-2 shadow-sm border-0">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge ${badge}">${m.tipo.toUpperCase()}</span>
                                    <small class="text-muted">${m.fecha ?? '—'}</small>
                                </div>
                                <div class="small text-muted">
                                    <i class="bi bi-arrow-left-right me-1"></i>
                                    ${m.origen ?? '—'} → ${m.externa ?? m.destino ?? '—'}
                                </div>
                                <div class="small text-muted">
                                    <i class="bi bi-person me-1"></i>
                                    ${m.solicitante ?? 'Sin solicitante'}
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
        });
    }
});
</script>
@endpush