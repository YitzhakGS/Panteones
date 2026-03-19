@extends('layouts.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/css-view/css_cards_conceciones.css') }}">
</head>

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-3 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-journal-bookmark-fill"></i> Concesiones
            </h4>
        </div>
    </div>
</div>

<div class="titulares-wrapper">

    {{-- Barra superior --}}
    <div class="titulares-header mb-3 row align-items-center">
        <div class="col-md-4 text-start">
            <button
                type="button"
                class="btn bg-base text-white"
                data-bs-toggle="modal"
                data-bs-target="#createConcesionModal">
                <i class="bi bi-plus-circle"></i> Nueva Concesión
            </button>
        </div>

        <div class="col-md-8">
            <form method="GET" action="{{ route('concesiones.index') }}">
                <input type="text"
                    id="searchConcesion"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control form-control-lg"
                    placeholder="Buscar por lote, titular, estatus o uso funerario...">
            </form>
        </div>
    </div>

    {{-- Cards --}}
    <div class="card-area">
        <div class="cards-scroll-container border rounded p-2 bg-light">

            <div class="card-container">

                @forelse ($concesiones as $concesion)

                    @php
                        $estatusNombre = $concesion->estatus->nombre;
                        $claseEstatus  = match($estatusNombre) {
                            'Al Corriente' => 'al-corriente',
                            'Con Adeudo'   => 'con-adeudo',
                            'Activa'       => 'activa',
                            'Inactiva'     => 'inactiva',
                            'Cancelada'    => 'cancelada',
                            default        => 'inactiva',
                        };
                    @endphp

                    {{-- Una sola card, con data-bs-toggle para abrir el modal --}}
                    <div class="concesion-card"
                        role="button"
                        data-bs-toggle="modal"
                        data-bs-target="#showConcesionModal"
                        data-id="{{ $concesion->id_concesion }}"
                        data-lote="{{ $concesion->lote->numero ?? '—' }}"
                        data-titular="{{ $concesion->titular->familia }}"
                        data-uso="{{ $concesion->usoFunerario->nombre }}"
                        data-tipo="{{ $concesion->tipo }}"
                        data-estatus="{{ $estatusNombre }}"
                        data-clase="{{ $claseEstatus }}"
                        data-inicio="{{ $concesion->fecha_inicio->format('d/m/Y') }}"
                        data-fin="{{ $concesion->fecha_fin ? $concesion->fecha_fin->format('d/m/Y') : 'Indefinida' }}"
                        data-anos-adeudo="{{ $concesion->anos_en_adeudo }}"
                        data-observaciones="{{ $concesion->observaciones ?? '' }}">

                        {{-- Franja lateral --}}
                        <div class="card-accent {{ $claseEstatus }}"></div>

                        <div class="card-inner">

                            {{-- Número de lote --}}
                            <div class="card-lote">
                                <i class="bi bi-geo-alt-fill lote-icon"></i>
                                <span class="lote-num">{{ $concesion->lote->numero ?? '—' }}</span>
                                <span class="lote-sub">Lote</span>
                            </div>

                            {{-- Datos --}}
                            <div class="card-data">
                                <div class="data-item">
                                    <span class="data-label">Titular</span>
                                    <span class="data-value">{{ $concesion->titular->familia }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Uso funerario</span>
                                    <span class="data-value">{{ $concesion->usoFunerario->nombre }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Fecha inicio</span>
                                    <span class="data-value">{{ $concesion->fecha_inicio->format('d/m/Y') }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Último refrendo</span>
                                    <span class="data-value">
                                        {{ optional($concesion->ultimoRefrendo)->fecha_refrendo?->format('d/m/Y') ?? '—' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Estatus --}}
                            <div class="card-status">
                                <span class="status-pill {{ $claseEstatus }}">
                                    <span class="status-dot"></span>
                                    {{ $estatusNombre }}
                                </span>
                            </div>

                        </div>
                    </div>

                @empty
                    <div class="alert alert-info text-center">
                        No hay concesiones registradas.
                    </div>
                @endforelse

            </div>

            {{-- Paginación --}}
            <div class="pagination-container d-flex justify-content-center mt-3">
                {{ $concesiones->links() }}
            </div>

        </div>
    </div>
</div>

@endsection

@include('concesiones.show')
@include('concesiones.create')


@push('scripts')
<script>
/**
 * Buscador en tiempo real
 */
document.getElementById('searchConcesion').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();
    document.querySelectorAll('.concesion-card').forEach(card => {
        card.style.display = card.innerText.toLowerCase().includes(value) ? '' : 'none';
    });
});

/**
 * Modal de detalle — rellena los campos con los datos de la card
 */
document.addEventListener('DOMContentLoaded', function () {
    const showModal = document.getElementById('showConcesionModal');
    if (!showModal) return;

    // Colores por estatus para el campo de texto del modal
    const estatusColores = {
        'Al Corriente': '#15803d',
        'Con Adeudo':   '#b91c1c',
        'Activa':       '#1d4ed8',
        'Inactiva':     '#475569',
        'Cancelada':    '#92400e',
    };

    showModal.addEventListener('show.bs.modal', function (event) {
        const card = event.relatedTarget;
        if (!card) return;

        const estatusNombre = card.dataset.estatus ?? '';

        // Rellenar campos
        document.getElementById('show_id_concesion').value  = card.dataset.id          ?? '';
        document.getElementById('show_lote').value          = card.dataset.lote         ?? '';
        document.getElementById('show_titular').value       = card.dataset.titular      ?? '';
        document.getElementById('show_uso').value           = card.dataset.uso          ?? '';
        document.getElementById('show_fecha_inicio').value  = card.dataset.inicio       ?? '';
        document.getElementById('show_fecha_fin').value     = card.dataset.fin          ?? 'N/A';
        document.getElementById('show_observaciones').value = card.dataset.observaciones ?? '';
        document.getElementById('show_tipo').value          = card.dataset.tipo         ?? '';
        document.getElementById('show_anos_adeudo').value   = card.dataset.anosAdeudo     ?? '0';


        // Campo estatus con color según los 5 estados
        const estatusInput = document.getElementById('show_estatus');
        estatusInput.value      = estatusNombre;
        estatusInput.style.color     = estatusColores[estatusNombre] ?? '#475569';
        estatusInput.style.fontWeight = estatusColores[estatusNombre] ? '700' : '400';
    });
});
</script>
@endpush