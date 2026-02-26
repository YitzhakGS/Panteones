<div class="modal fade" id="showLoteModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">

            {{-- HEADER con borde inferior más marcado --}}
            <div class="modal-header bg-white border-bottom border-2 border-light">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>
                    Detalle del Lote
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY con fondo gris suave --}}
            <div class="modal-body bg-light" style="background-color: #f4f6f9 !important;">

                <div class="row g-4">

                    {{-- IDENTIFICACIÓN (Número y Superficie) --}}
                    <div class="col-md-3">
                        <div class="card h-100 border-0 shadow-sm card-hover">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary fw-semibold small mb-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Identificación
                                </h6>

                                <div class="mb-4">
                                    <small class="text-muted text-uppercase fw-semibold">Número de lote</small>
                                    <div class="fs-4 fw-bold bg-white rounded-3 p-3 mt-1 border-start border-4 border-primary shadow-inner">
                                        <span id="show_lote_numero"></span>
                                    </div>
                                </div>

                                <div>
                                    <small class="text-muted text-uppercase fw-semibold">Superficie</small>
                                    <div class="fs-4 fw-bold bg-white rounded-3 p-3 mt-1 border-start border-4 border-success shadow-inner">
                                        <span id="show_lote_superficie"></span> m²
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MEDIDAS (con cuadros más grandes y con sombra) --}}
                    <div class="col-md-2">
                        <div class="card h-100 border-0 shadow-sm card-hover">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary fw-semibold small mb-3">
                                    <i class="bi bi-rulers me-1"></i>
                                    Medidas (m)
                                </h6>

                                <div class="d-flex flex-column gap-2">
                                    @php
                                        $medidas = [
                                            'Norte' => 'show_med_norte',
                                            'Sur' => 'show_med_sur',
                                            'Oriente' => 'show_med_oriente',
                                            'Poniente' => 'show_med_poniente'
                                        ];
                                    @endphp

                                    @foreach($medidas as $label => $id)
                                        <div class="bg-white rounded-3 p-2 px-3 shadow-sm border">
                                            <small class="text-muted text-uppercase fw-semibold d-block">
                                                {{ $label }}
                                            </small>
                                            <span class="fw-bold fs-5 text-dark" id="{{ $id }}"></span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- COLINDANCIAS (con estilo más compacto pero mejorado) --}}
                    <div class="col-md-7">
                        <div class="card h-100 border-0 shadow-sm card-hover">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary fw-semibold small mb-3">
                                    <i class="bi bi-diagram-3 me-1"></i>
                                    Colindancias
                                </h6>

                                <div class="d-flex flex-column gap-2">
                                    @php
                                        $colindancias = [
                                            'Norte' => 'show_col_norte',
                                            'Sur' => 'show_col_sur',
                                            'Oriente' => 'show_col_oriente',
                                            'Poniente' => 'show_col_poniente'
                                        ];
                                    @endphp
                                    @foreach($colindancias as $label => $id)
                                    <div class="bg-white rounded-3 p-3 shadow-sm d-flex justify-content-between align-items-center border">
                                        <span class="text-muted fw-semibold">{{ $label }}</span>
                                        <span class="fw-bold text-dark" id="{{ $id }}"></span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- REFERENCIAS --}}
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm card-hover">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary fw-bold small mb-3">
                                    <i class="bi bi-chat-left-text me-1"></i>
                                    Referencias
                                </h6>
                                <div class="bg-white rounded-3 p-4 shadow-inner border">
                                    <p id="show_lote_referencias" class="mb-0 fs-5 text-dark">
                                        —
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- UBICACIÓN --}}
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm card-hover">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary fw-bold small mb-3">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Ubicación actual
                                </h6>
                                <div class="bg-white rounded-3 p-4 shadow-inner border">
                                    <p id="show_lote_ubicacion" class="mb-0 fs-5 text-dark">
                                        —
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer bg-white border-top border-2 border-light">
                <button type="button"class="btn bg-base text-white shadow-sm" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>