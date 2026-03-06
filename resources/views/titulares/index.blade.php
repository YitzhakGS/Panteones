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
                        data-telefono="{{ $titular->telefono }}">

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
/**
 * Gestión del Módulo de Titulares
 * Este script maneja el filtrado dinámico de tarjetas, el scroll de paginación
 * y la carga de datos detallados en el modal de visualización.
 */

/**
 * Buscador en tiempo real para las tarjetas de Titulares.
 * Filtra las tarjetas basándose en el texto ingresado (insensible a mayúsculas).
 */
document.getElementById('searchTitular').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();
    
    // Iteramos sobre todas las tarjetas de titulares disponibles en el DOM
    document.querySelectorAll('.titular-card').forEach(card => {
        // Mostramos u ocultamos la tarjeta según si el texto coincide
        card.style.display = card.innerText.toLowerCase().includes(value)
            ? ''
            : 'none';
    });
});

/**
 * Manejador de scroll para la paginación.
 * Asegura que al cambiar de página, la vista regrese al inicio del contenedor.
 */
document.querySelectorAll('.pagination a').forEach(link => {
    link.addEventListener('click', () => {
        document.querySelector('.cards-scroll-container')
            ?.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

/**
 * Inicialización de eventos al cargar el DOM.
 */
document.addEventListener('DOMContentLoaded', function () {

    const showModal = document.getElementById('showTitularModal');

    if (showModal) {
        /**
         * Evento de Bootstrap que se dispara antes de mostrar el modal.
         * @param {Event} event - El evento de Bootstrap que contiene el disparador.
         */
        showModal.addEventListener('show.bs.modal', function (event) {
            // Elemento (tarjeta o botón) que activó el modal
            const card = event.relatedTarget;

            // Llenado de los campos del formulario de visualización (lectura)
            document.getElementById('show_id').value        = card.dataset.id;
            document.getElementById('show_familia').value   = card.dataset.familia;
            document.getElementById('show_domicilio').value = card.dataset.domicilio;
            document.getElementById('show_colonia').value   = card.dataset.colonia;
            document.getElementById('show_cp').value        = card.dataset.cp;
            document.getElementById('show_municipio').value = card.dataset.municipio;
            document.getElementById('show_estado').value    = card.dataset.estado;
            
            // Uso de Operador Nullish (??) para mostrar un guion si no hay teléfono
            document.getElementById('show_telefono').value  = card.dataset.telefono ?? '—';
        });
    }
});
</script>
@endpush