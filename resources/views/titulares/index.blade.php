    @extends('layouts.app')
    <head>
        <!-- CSS global -->
        <link rel="stylesheet" href="{{ asset('css/css-view/css_cards.css') }}">

        @stack('styles')
    </head>


    @section('content')
    <div class="row">
        <div class="col-12">
            <div class=" page-title-box mb-3 d-flex align-items-center justify-content-between" style="padding-bottom: 5px; margin-bottom: 0 !important;">
                <h4 class="page-title mb-0 font-size-18">
                    <i class="bi bi-people"></i> Titulares
                </h4>
            </div>
        </div>
    </div>
    <div class="titulares-wrapper">
        {{-- Barra de búsqueda + botón --}}
        <div class="titulares-header mb-3 row align-items-center">
            <div class="col-md-4 text-start">
                <button type="button" class="btn bg-base text-white mb-2"
                    data-bs-toggle="modal" data-bs-target="#createTitularModal">
                    <i class="bi bi-plus-circle"></i> Nuevo Titular
                </button>
            </div>
            <div class="col-md-8">
                <input type="text" id="searchTitular" class="form-control form-control-lg"
                    placeholder="Buscar por familia, domicilio, colonia o teléfono...">
            </div>
        </div>

        {{-- Contenedor principal CARDS --}}
        <div class="card-area">

            {{-- Scroll container (como tu ejemplo) --}}
            <div class="cards-scroll-container border rounded p-2 bg-light">

                {{-- Cards --}}
                <div class="card-container">
                    @forelse ($titulares as $titular)
                        <div class="card card-titular mb-2 titular-card shadow-sm"
                            role="button"
                            style="cursor: pointer"
                            data-bs-toggle="modal"
                            data-bs-target="#showTitularModal"

                            {{-- DATA PARA EL MODAL --}}
                            data-id="{{ $titular->id_titular }}"
                            data-familia="{{ $titular->familia }}"
                            data-domicilio="{{ $titular->domicilio }}"
                            data-colonia="{{ $titular->colonia }}"
                            data-cp="{{ $titular->codigo_postal }}"
                            data-municipio="{{ $titular->municipio }}"
                            data-estado="{{ $titular->estado }}"
                            data-telefono="{{ $titular->telefono }}"
                        >
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                                    <h5 class="card-title-text fw-bold mb-0 text-truncate">
                                        <i class="bi bi-person-vcard me-2"></i>{{ $titular->familia }}
                                    </h5>
                                </div>

                                <div class="info-flex-container">
                                    <div class="info-group main-address">
                                        <span class="info-label">
                                            Dirección 
                                            <small style="text-transform: lowercase; font-weight: normal; opacity: 0.8;">
                                                (Calle, No. Ext/Int y Colonia)
                                            </small>
                                        </span>
                                        <span class="info-value" style="padding-left: 10px;">
                                            {{ $titular->domicilio }}, {{ $titular->colonia }}
                                        </span>
                                    </div>
                                    <div class="info-group location-details">
                                        <div class="info-sub-item">
                                            <span class="info-label">C.P.</span>
                                            <span class="info-value">{{ $titular->codigo_postal }}</span>
                                        </div>

                                        <div class="info-sub-item d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="info-label">Municipio / Estado</span>
                                                <span class="info-value">
                                                    {{ $titular->municipio }}, {{ $titular->estado }}
                                                </span>
                                            </div>

                                            <span class="badge text-bg-secondary bg-light text-dark border fs-6 py-2 px-3">
                                                <i class="bi bi-telephone-fill me-2"></i>
                                                {{ $titular->telefono ?? '—' }}
                                            </span>
                                        </div>
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

            </div>

            {{-- PAGINACIÓN FUERA DEL SCROLL --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $titulares->links() }}
            </div>
        </div>
    </div>


    {{-- Modal create --}}
    @include('titulares.create')
    @include('titulares.show')  

    @endsection

    @push('scripts')
    <script>
    document.getElementById('searchTitular').addEventListener('keyup', function () {
        const value = this.value.toLowerCase();
        document.querySelectorAll('.titular-card').forEach(card => {
            card.style.display = card.innerText.toLowerCase().includes(value)
                ? ''
                : 'none';
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

                document.getElementById('show_id').value           = card.dataset.id;
                document.getElementById('show_familia').value         = card.dataset.familia;
                document.getElementById('show_domicilio').value       = card.dataset.domicilio;
                document.getElementById('show_colonia').value         = card.dataset.colonia;
                document.getElementById('show_cp').value              = card.dataset.cp;
                document.getElementById('show_municipio').value       = card.dataset.municipio;
                document.getElementById('show_estado').value          = card.dataset.estado;
                document.getElementById('show_telefono').value        = card.dataset.telefono ?? '—';
            });
        }
    });
    </script>
    @endpush
