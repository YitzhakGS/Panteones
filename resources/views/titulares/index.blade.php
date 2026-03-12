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
                <i class="bi bi-people"></i> Titulares
            </h4>
        </div>
    </div>
</div>

<div class="titulares-wrapper">

    {{-- Barra superior --}}
    <div class="titulares-header mb-3 row align-items-center">
        <div class="col-md-4 text-start">
            <button type="button" class="btn bg-base text-white"
                data-bs-toggle="modal" data-bs-target="#createTitularModal">
                <i class="bi bi-plus-circle"></i> Nuevo Titular
            </button>
        </div>
        <div class="col-md-8">
            <input type="text" id="searchTitular" class="form-control form-control-lg"
                placeholder="Buscar por familia, domicilio, colonia o teléfono...">
        </div>
    </div>

    {{-- Cards --}}
    <div class="card-area">
        <div class="cards-scroll-container border rounded p-2 bg-light">

            <div class="card-container">
                @forelse ($titulares as $titular)

                    @php
                        // iniciales para el avatar: se toman las primeras letras de las dos primeras palabras del nombre de la familia
                        $palabras   = explode(' ', trim($titular->familia));
                        $iniciales  = strtoupper(substr($palabras[0] ?? '', 0, 1) . substr($palabras[1] ?? '', 0, 1));
                    @endphp

                    <div class="titular-card"
                        role="button"
                        data-bs-toggle="modal"
                        data-bs-target="#showTitularModal"
                        data-id="{{ $titular->id_titular }}"
                        data-familia="{{ $titular->familia }}"
                        data-domicilio="{{ $titular->domicilio }}"
                        data-colonia="{{ $titular->colonia }}"
                        data-cp="{{ $titular->codigo_postal }}"
                        data-municipio="{{ $titular->municipio }}"
                        data-estado="{{ $titular->estado }}"
                        data-telefono="{{ $titular->telefono }}"
                        data-documentos='@json(
                        $titular->documentos->map(fn($d) => [
                        "id"=>$d->id_documento,
                        "id_tipo_documento"=>$d->id_tipo_documento,
                        "nombre"=>$d->tipoDocumento->nombre
                        ])
                        )'>

                        {{-- Avatar lateral --}}
                        <div class="card-avatar">
                            <div class="avatar-initials">{{ $iniciales }}</div>
                            <i class="bi bi-person avatar-icon"></i>
                        </div>

                        {{-- Contenido --}}
                        <div class="card-content">

                            {{-- Fila superior: nombre + CP --}}
                            <div class="card-row-top">
                                <span class="titular-nombre">{{ $titular->familia }}</span>
                                <span class="titular-cp">C.P. {{ $titular->codigo_postal }}</span>
                            </div>

                            <div class="card-divider"></div>

                            {{-- Fila inferior: chips de datos --}}
                            <div class="card-row-bottom">
                                <div class="data-chip">
                                    <i class="bi bi-house"></i>
                                    <span class="chip-text">{{ $titular->domicilio }}</span>
                                </div>
                                <span class="chip-sep">•</span>
                                <div class="data-chip">
                                    <i class="bi bi-signpost"></i>
                                    <span class="chip-text">{{ $titular->colonia }}</span>
                                </div>
                                <span class="chip-sep">•</span>
                                <div class="data-chip">
                                    <i class="bi bi-geo-alt"></i>
                                    <span class="chip-text">{{ $titular->municipio }}, {{ $titular->estado }}</span>
                                </div>
                                <div class="data-chip phone">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span class="chip-text">{{ $titular->telefono ?? '—' }}</span>
                                </div>

                                {{-- Chips documentos --}}
                                <span class="chip-sep">•</span>
                                @if($titular->documentos->isNotEmpty())
                                    @foreach($titular->documentos as $doc)
                                        <div class="data-chip" style="background-color: #d1fae5; color: #065f46;">
                                            <i class="bi bi-file-earmark-check"></i>
                                            <span class="chip-text">{{ $doc->tipoDocumento->nombre }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="data-chip" style="background-color: #f3f4f6; color: #6b7280;">
                                        <i class="bi bi-file-earmark-x"></i>
                                        <span class="chip-text">Sin documentos</span>
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>

                @empty
                    <div class="alert alert-info text-center">
                        No hay titulares registrados.
                    </div>
                @endforelse
            </div>

            @if(method_exists($titulares, 'links'))
                <div class="pagination-container d-flex justify-content-center mt-3">
                    {{ $titulares->links() }}
                </div>
            @endif

        </div>
    </div>
</div>


{{-- Modal create --}}
@include('titulares.create')
@if ($titulares->count() > 0)
    @include('titulares.show') 
@endif


@endsection
@push('scripts')
<script>
document.getElementById('searchTitular').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();
    document.querySelectorAll('.titular-card').forEach(card => {
        card.style.display = card.innerText.toLowerCase().includes(value) ? '' : 'none';
    });
});

document.querySelectorAll('.pagination a').forEach(link => {
    link.addEventListener('click', () => {
        document.querySelector('.cards-scroll-container')
            ?.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

document.addEventListener('DOMContentLoaded', function () {

    const showModal = document.getElementById('showTitularModal');

    if (showModal) {
        showModal.addEventListener('show.bs.modal', function (event) {
            const card = event.relatedTarget;
        document.getElementById('show_id').value              = card.dataset.id;
        document.getElementById('show_familia').textContent   = card.dataset.familia;
        document.getElementById('show_domicilio').textContent = card.dataset.domicilio;
        document.getElementById('show_colonia').textContent   = card.dataset.colonia;
        document.getElementById('show_cp').textContent        = card.dataset.cp;
        document.getElementById('show_municipio').textContent = card.dataset.municipio;
        document.getElementById('show_estado').textContent    = card.dataset.estado;
        document.getElementById('show_telefono').textContent  = card.dataset.telefono ?? '—';

            // Documentos
            const documentos = JSON.parse(card.dataset.documentos || '[]');
            const container  = document.getElementById('show_documentos_container');
            container.innerHTML = '';

            if (documentos.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-file-earmark-x me-1"></i>Sin documentos registrados
                        </p>
                    </div>`;
            } else {
                documentos.forEach(doc => {
                    container.innerHTML += `
                        <div class="${documentos.length === 1 ? 'col-12' : 'col-md-6'}">
                            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light border">
                                <span class="fw-semibold small">
                                    <i class="bi bi-file-earmark-check me-1 text-success"></i>
                                    ${doc.nombre}
                                </span>
                                <a href="/documentos/${doc.id}" target="_blank"
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </a>
                            </div>
                        </div>`;
                });
            }
        });
    }
});
</script>
@endpush