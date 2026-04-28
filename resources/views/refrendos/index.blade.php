@extends('layouts.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/css-view/css_cards_conceciones.css') }}">
</head>

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-3 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-arrow-repeat"></i> Refrendos
            </h4>
        </div>
    </div>
</div>

<div class="titulares-wrapper">

    {{-- Barra superior --}}
    <div class="titulares-header mb-3 row align-items-center g-2">

        {{-- Filtros de estado --}}
        <div class="col-md-4">
            <div class="d-flex gap-2 flex-wrap">
                @foreach(['todos' => 'Todos', 'pendientes' => 'Pendiente', 'vencidos' => 'Vencido', 'pagados' => 'Pagado', 'cancelado' => 'Cancelado'] as $valor => $etiqueta)
                    <a href="{{ route('refrendos.index', ['estado' => $valor]) }}"
                    class="btn btn-sm {{ request('estado') === $valor ? 'bg-base text-white' : 'btn-outline-secondary' }}">
                        {{ $etiqueta }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Buscador --}}
        <div class="col-md-8">
            <form method="GET" action="{{ route('refrendos.index') }}">
                <input type="hidden" name="estado" value="">  {{-- ← limpia el estado al buscar --}}
                <input type="text"
                    id="searchRefrendo"
                    name="search"
                    class="form-control form-control-lg"
                    placeholder="Buscar por lote o titular..."
                    value="{{ request('search') }}">
            </form>
        </div>

    </div>

    {{-- Cards --}}
    <div class="card-area">
        <div class="cards-scroll-container border rounded p-2 bg-light">
            <div class="card-container">

                @forelse ($refrendos as $refrendo)

                    @php
                        $estadoLabel = $refrendo->estado_label;
                        $claseEstado = match($estadoLabel) {
                            'Pendiente' => 'activa',
                            'Pagado'    => 'al-corriente',
                            'Vencido'   => 'con-adeudo',
                            'Cancelado' => 'cancelada',
                            default     => 'inactiva',
                        };
                    @endphp

                    <div class="concesion-card"
                         role="button"
                         data-bs-toggle="modal"
                         data-bs-target="#showRefrendoModal"
                         data-id="{{ $refrendo->id_refrendo }}"
                         data-lote="{{ $refrendo->concesion->lote->numero ?? '—' }}"
                         data-titular="{{ $refrendo->concesion->titular->familia ?? '—' }}"
                         data-tipo="{{ $refrendo->tipo_refrendo }}"
                         data-monto="{{ $refrendo->monto ?? '0.00' }}"
                         data-fecha-limite="{{ $refrendo->fecha_limite_pago?->format('d/m/Y') ?? '—' }}"
                         data-fecha-inicio="{{ $refrendo->fecha_inicio->format('d/m/Y') }}"
                         data-fecha-fin="{{ $refrendo->fecha_fin->format('d/m/Y') }}"
                         data-estado="{{ $estadoLabel }}"
                         data-clase="{{ $claseEstado }}"
                         data-pago-id="{{ $refrendo->pago?->id_pago }}"
                         data-observaciones="{{ $refrendo->observaciones ?? '' }}"
                         data-tiene-pago="{{ $refrendo->pago ? '1' : '0' }}"
                         data-pago-fecha="{{ $refrendo->pago?->fecha_pago?->format('d/m/Y') }}"
                         data-pago-monto="{{ $refrendo->pago?->monto }}"
                         data-pago-folio="{{ $refrendo->pago?->folio_ticket }}"
                         data-pago-forma="{{ $refrendo->pago?->forma_pago }}">

                        <div class="card-accent {{ $claseEstado }}"></div>

                        <div class="card-inner">

                            {{-- Lote --}}
                            <div class="card-lote">
                                <i class="bi bi-geo-alt-fill lote-icon"></i>
                                <span class="lote-num">{{ $refrendo->concesion->lote->numero ?? '—' }}</span>
                                <span class="lote-sub">Lote</span>
                            </div>

                            {{-- Datos --}}
                            <div class="card-data">
                                <div class="data-item">
                                    <span class="data-label">Titular</span>
                                    <span class="data-value">{{ $refrendo->concesion->titular->familia ?? '—' }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Monto</span>
                                    <span class="data-value">${{ number_format($refrendo->monto ?? 0, 2) }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Fecha límite</span>
                                    <span class="data-value">
                                        {{ $refrendo->fecha_limite_pago?->format('d/m/Y') ?? '—' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Estado --}}
                            <div class="card-status">
                                <span class="status-pill {{ $claseEstado }}">
                                    <span class="status-dot"></span>
                                    {{ $estadoLabel }}
                                </span>
                            </div>

                        </div>
                    </div>

                @empty
                    <div class="alert alert-info text-center">
                        No hay refrendos registrados.
                    </div>
                @endforelse

            </div>

            <div class="pagination-container d-flex justify-content-center mt-3" style="margin-bottom: 80px;">
                {{ $refrendos->links() }}
            </div>

        </div>
    </div>
</div>

@endsection

@include('refrendos.show')

{{-- Modal fecha límite global --}}
<div class="modal fade" id="fechaLimiteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-calendar-range me-2 text-muted"></i>Fecha límite global
                    </h5>
                    <p class="text-muted small mb-0">
                        Se asignará a todos los refrendos pendientes
                        @if($fechaLimitePago)
                            · Actual: <strong>{{ \Carbon\Carbon::parse($fechaLimitePago)->format('d/m/Y') }}</strong>
                        @endif
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('refrendos.setFechaLimite') }}" method="POST">
                @csrf
                <div class="modal-body pt-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-x me-1 text-muted"></i>Nueva fecha límite de pago
                    </label>
                    <input type="date"
                           name="fecha_limite_pago"
                           class="form-control"
                           value="{{ $fechaLimitePago ?? '' }}"
                           required>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn bg-base text-white px-4">
                        <i class="bi bi-check2-circle me-1"></i>Asignar fecha
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalShow = document.getElementById('showRefrendoModal');
    const modalPagoEl = document.getElementById('pagoRefrendoModal');
    const formPago = document.getElementById('formPagoRefrendo');

    // Función para resetear backdrops y cerrar
    function forceHideShow() {
        const inst = bootstrap.Modal.getInstance(modalShow);
        if (inst) inst.hide();
        // Limpieza de emergencia para evitar pantalla negra
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style = "";
    }

    // --- ACCIÓN: REGISTRAR NUEVO PAGO ---
    document.getElementById('btnRegistrarPago')?.addEventListener('click', function() {
        const id = document.getElementById('show_id_refrendo').value;
        const card = document.querySelector(`.concesion-card[data-id="${id}"]`);

        forceHideShow();

        setTimeout(() => {
            // Configurar para CREAR
            document.getElementById('titulo-modal').innerText = 'Registrar Pago';
            document.getElementById('btn-submit-pago').querySelector('span').innerText = 'Registrar pago';
            document.getElementById('metodo-edicion').innerHTML = ''; // Limpiar PUT
            formPago.action = "{{ route('pagos.store') }}";

            // Llenar datos básicos
            document.getElementById('pago_id_refrendo').value = id;
            document.getElementById('pago_lote').value = card.dataset.lote;
            document.getElementById('pago_titular').value = card.dataset.titular;
            document.getElementById('pago_monto').value = card.dataset.monto;
            
            // Limpiar campos variables
            document.getElementById('pago_fecha').value = new Date().toISOString().split('T')[0];
            document.getElementById('pago_folio').value = '';
            document.getElementById('pago_obs').value = '';

            new bootstrap.Modal(modalPagoEl).show();
        }, 400);
    });

    // --- ACCIÓN: EDITAR PAGO EXISTENTE ---
    document.getElementById('btnEditarPago')?.addEventListener('click', function() {
        const id = document.getElementById('show_id_refrendo').value;
        const card = document.querySelector(`.concesion-card[data-id="${id}"]`);

        forceHideShow();

        setTimeout(() => {
            // Configurar para EDITAR
            document.getElementById('titulo-modal').innerText = 'Editar Pago';
            document.getElementById('btn-submit-pago').querySelector('span').innerText = 'Actualizar pago';
            document.getElementById('metodo-edicion').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            formPago.action = `/pagos/${card.dataset.pagoId}`;

            // Inyectar datos cargados de la card
            document.getElementById('pago_id_refrendo').value = id;
            document.getElementById('pago_lote').value = card.dataset.lote;
            document.getElementById('pago_titular').value = card.dataset.titular;
            document.getElementById('pago_monto').value = card.dataset.pagoMonto;
            document.getElementById('pago_fecha').value = card.dataset.pagoFecha; // Asegúrate que venga en formato YYYY-MM-DD
            document.getElementById('pago_forma').value = card.dataset.pagoForma;
            document.getElementById('pago_folio').value = card.dataset.pagoFolio;
            document.getElementById('pago_obs').value = card.dataset.observaciones;

            new bootstrap.Modal(modalPagoEl).show();
        }, 400);
    });
});
</script>
@endpush