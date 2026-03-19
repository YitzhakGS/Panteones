@extends('layouts.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/css-view/css_cards_titulares.css') }}">
</head>

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-3 d-flex align-items-center justify-content-between"
             style="padding-bottom: 5px; margin-bottom: 0 !important;">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-people"></i> Beneficiarios
            </h4>
        </div>
    </div>
</div>

<div class="titulares-wrapper">

    {{-- Barra superior --}}
    <div class="titulares-header mb-3 row align-items-center">
        <div class="col-md-4 text-start">
            <button type="button" class="btn bg-base text-white"
                data-bs-toggle="modal" data-bs-target="#createBeneficiarioModal">
                <i class="bi bi-plus-circle"></i> Nuevo Beneficiario
            </button>
        </div>
        <div class="col-md-8">
            <form method="GET" action="{{ route('beneficiarios.index') }}">
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control form-control-lg"
                    placeholder="Buscar por nombre, domicilio o colonia...">
            </form>
        </div>
    </div>

    {{-- Cards --}}
    <div class="card-area">
        <div class="cards-scroll-container border rounded p-2 bg-light">

            <div class="card-container">
                @forelse ($beneficiarios as $beneficiario)

                    @php
                        $palabras   = explode(' ', trim($beneficiario->nombre));
                        $iniciales  = strtoupper(substr($palabras[0] ?? '', 0, 1) . substr($palabras[1] ?? '', 0, 1));
                    @endphp

                    <div class="titular-card"
                        role="button"
                        data-bs-toggle="modal"
                        data-bs-target="#showBeneficiarioModal"
                        data-id="{{ $beneficiario->id_beneficiario }}"
                        data-nombre="{{ $beneficiario->nombre }}"
                        data-domicilio="{{ $beneficiario->domicilio }}"
                        data-colonia="{{ $beneficiario->colonia }}"
                        data-cp="{{ $beneficiario->codigo_postal }}"
                        data-municipio="{{ $beneficiario->municipio }}"
                        data-estado="{{ $beneficiario->estado }}"
                        data-telefono="{{ $beneficiario->telefono }}"
                        data-titular="{{ optional($beneficiario->titular)->familia }}"
                        data-orden="{{ $beneficiario->orden }}"
                        data-documentos='@json(
                            $beneficiario->documentos->map(fn($d) => [
                                "id"=>$d->id_documento,
                                "id_tipo_documento"=>$d->id_tipo_documento,
                                "nombre"=>$d->tipoDocumento->nombre
                            ])
                        )'>

                        {{-- Avatar --}}
                        <div class="card-avatar" style="background: linear-gradient(160deg, #064d5f 0%, #059196 100%);">
                            <div class="avatar-initials">{{ $iniciales }}</div>
                            <i class="bi bi-person avatar-icon"></i>
                        </div>

                        {{-- Contenido --}}
                        <div class="card-content">

                            <div class="card-row-top">
                                <span class="titular-nombre">{{ $beneficiario->nombre }}</span>
                                <span class="titular-cp">#{{ $beneficiario->orden }}</span>
                            </div>

                            <div class="card-divider"></div>
                            <div class="card-row-bottom">

                                <div style="display:inline-flex; align-items:center; gap:4px; font-size:.90rem; font-weight:600; color:#065f46; background:#d1fae5; border:1px solid #6ee7b7; border-radius:20px; padding:2px 10px; white-space:nowrap; flex-shrink:0;">
                                    <i class="bi bi-person-check" style="font-size:.65rem; color:#065f46;"></i>
                                    <span>Titular: {{ optional($beneficiario->titular)->familia ?? '—' }}</span>
                                </div>

                                <div class="data-chip">
                                    <i class="bi bi-house"></i>
                                    <span class="chip-text">{{ $beneficiario->domicilio }}</span>
                                </div>

                                <span class="chip-sep">•</span>

                                <div class="data-chip">
                                    <i class="bi bi-signpost"></i>
                                    <span class="chip-text">{{ $beneficiario->colonia }}</span>
                                </div>

                                <span class="chip-sep">•</span>

                                <div class="data-chip">
                                    <i class="bi bi-geo-alt"></i>
                                    <span class="chip-text">{{ $beneficiario->municipio }}, {{ $beneficiario->estado }}</span>
                                </div>

                                <div class="data-chip phone">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span class="chip-text">{{ $beneficiario->telefono ?? '—' }}</span>
                                </div>

                                {{-- Documentos --}}
                                <span class="chip-sep">•</span>

                                @if($beneficiario->documentos->isNotEmpty())
                                    @foreach($beneficiario->documentos as $doc)
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
                        No hay beneficiarios registrados.
                    </div>
                @endforelse
            </div>

            {{-- Paginación --}}
            @if(method_exists($beneficiarios, 'links'))
                <div class="pagination-container d-flex justify-content-center mt-3">
                    {{ $beneficiarios->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Modales --}}
@include('beneficiarios.create')
@if ($beneficiarios->count() > 0)
    @include('beneficiarios.show') 
@endif

@endsection

@push('scripts')
<script>

document.querySelectorAll('.pagination a').forEach(link => {
    link.addEventListener('click', () => {
        document.querySelector('.cards-scroll-container')
            ?.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

        document.addEventListener('DOMContentLoaded', function () {

            // 👇 CAMBIA AQUÍ (era showTitularModal)
            const showModal = document.getElementById('showBeneficiarioModal');

            if (showModal) {
                showModal.addEventListener('show.bs.modal', function (event) {
            const card = event.relatedTarget;

            // 1. Guardamos los documentos en la variable global para el Edit
            const docsData = JSON.parse(card.dataset.documentos || '[]');
            beneficiarioActual = { documentos: docsData };

            // 2. Llenamos los campos del Show usando los data-attributes de la card
            document.getElementById('show_id').value              = card.dataset.id;
            document.getElementById('show_orden').value           = card.dataset.orden ?? ''; 
            document.getElementById('show_nombre').textContent    = card.dataset.nombre;
            document.getElementById('show_domicilio').textContent = card.dataset.domicilio;
            document.getElementById('show_colonia').textContent   = card.dataset.colonia;
            document.getElementById('show_cp').textContent        = card.dataset.cp;
            document.getElementById('show_municipio').textContent = card.dataset.municipio;
            document.getElementById('show_estado').textContent    = card.dataset.estado;
            document.getElementById('show_telefono').textContent  = card.dataset.telefono || '—';
            document.getElementById('show_titular').textContent   = card.dataset.titular || '—';

            // 3. Renderizamos los documentos en el contenedor del Show
            const container = document.getElementById('show_documentos_container');
            container.innerHTML = '';

            if (docsData.length === 0) {
                container.innerHTML = `<div class="col-12"><p class="text-muted small mb-0"><i class="bi bi-file-earmark-x me-1"></i>Sin documentos</p></div>`;
            } else {
                docsData.forEach(doc => {
                    container.innerHTML += `
                        <div class="${docsData.length === 1 ? 'col-12' : 'col-md-6'}">  {{-- ✅ dinámico --}}
                            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light border">
                                <span class="fw-semibold small text-truncate" style="max-width: 150px;">
                                    <i class="bi bi-file-earmark-check me-1 text-success"></i>${doc.nombre}
                                </span>
                                <a href="/documentos/${doc.id}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
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