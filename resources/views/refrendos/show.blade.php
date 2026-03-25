<div class="modal fade" id="showRefrendoModal" tabindex="-1"
     aria-labelledby="showRefrendoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="showRefrendoModalLabel">
                        <i class="bi bi-arrow-repeat me-2 text-muted"></i>Detalle del Refrendo
                    </h5>
                    <p class="text-muted small mb-0">Información registrada del refrendo</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <input type="hidden" id="show_id_refrendo">

            <div class="modal-body pt-3">

                {{-- SECCIÓN 1: Concesión --}}
                <div class="section-block mb-3">
                    <span class="section-label">Concesión</span>
                    <div class="row g-3 mt-1">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-geo-alt me-1 text-muted"></i>Lote
                            </label>
                            <input type="text" id="show_r_lote"
                                   class="form-control bg-light border-0" readonly>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person me-1 text-muted"></i>Titular
                            </label>
                            <input type="text" id="show_r_titular"
                                   class="form-control bg-light border-0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tag me-1 text-muted"></i>Tipo de refrendo
                            </label>
                            <input type="text" id="show_r_tipo"
                                   class="form-control bg-light border-0 text-capitalize" readonly>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 2: Fechas --}}
                <div class="section-block mb-3">
                    <span class="section-label">Fechas del periodo</span>
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-1 text-muted"></i>Inicio del periodo
                            </label>
                            <input type="text" id="show_r_fecha_inicio"
                                   class="form-control bg-light border-0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-x me-1 text-muted"></i>Fin del periodo
                            </label>
                            <input type="text" id="show_r_fecha_fin"
                                   class="form-control bg-light border-0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-exclamation-circle me-1 text-muted"></i>Fecha límite de pago
                            </label>
                            <input type="text" id="show_r_fecha_limite"
                                   class="form-control bg-light border-0" readonly>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 3: Monto y estado --}}
                <div class="section-block mb-3">
                    <span class="section-label">Pago</span>
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-cash-coin me-1 text-muted"></i>Monto
                            </label>
                            <input type="text" id="show_r_monto"
                                   class="form-control bg-light border-0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-circle-fill me-1 text-muted" style="font-size:.5rem;vertical-align:middle"></i>Estado
                            </label>
                            <input type="text" id="show_r_estado"
                                   class="form-control bg-light border-0 fw-bold" readonly>
                        </div>

                        {{-- Info del pago registrado (visible solo si ya está pagado) --}}
                        <div id="show_r_pago_info" style="display:none">
                            <div class="row g-3 mt-1">

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Fecha de pago</label>
                                    <input type="text" id="show_r_pago_fecha" class="form-control bg-light border-0" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Monto pagado</label>
                                    <input type="text" id="show_r_pago_monto" class="form-control bg-light border-0" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Folio</label>
                                    <input type="text" id="show_r_folio" class="form-control bg-light border-0" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Forma de pago</label>
                                    <input type="text" id="show_r_pago_forma" class="form-control bg-light border-0" readonly>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 4: Observaciones + Acciones --}}
                <div class="section-block mb-1">
                    <span class="section-label">Observaciones</span>
                    <div class="row g-3 mt-1 align-items-end">
                        <div class="col-md-8">
                            <textarea id="show_r_observaciones" rows="2"
                                      class="form-control bg-light border-0" readonly
                                      placeholder="Sin observaciones..."></textarea>
                        </div>
                        <div class="col-md-4 d-flex flex-column gap-2">
                            <button type="button" class="btn bg-base text-white w-100 d-none" id="btnRegistrarPago">
                                <i class="bi bi-cash-stack me-1"></i>Registrar pago
                            </button>
                            
                            <button type="button" class="btn btn-warning w-100 d-none" id="btnEditarPago">
                                <i class="bi bi-pencil me-1"></i>Editar pago
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('refrendos.pago')

<script>
document.getElementById('showRefrendoModal').addEventListener('show.bs.modal', function (event) {
    const card = event.relatedTarget;
    if (!card) return;

    // Llenado de campos básicos
    document.getElementById('show_id_refrendo').value = card.dataset.id;
    document.getElementById('show_r_lote').value = card.dataset.lote;
    document.getElementById('show_r_titular').value = card.dataset.titular;
    document.getElementById('show_r_tipo').value = card.dataset.tipo;
    document.getElementById('show_r_fecha_inicio').value = card.dataset.fechaInicio;
    document.getElementById('show_r_fecha_fin').value = card.dataset.fechaFin;
    document.getElementById('show_r_fecha_limite').value = card.dataset.fechaLimite;
    document.getElementById('show_r_monto').value = '$' + parseFloat(card.dataset.monto || 0).toFixed(2);
    document.getElementById('show_r_observaciones').value = card.dataset.observaciones;
    document.getElementById('show_r_estado').value = card.dataset.estado;

    // Lógica de botones
    const estado = card.dataset.estado.toLowerCase();
    const btnPago = document.getElementById('btnRegistrarPago');
    const btnEdit = document.getElementById('btnEditarPago');
    const infoPago = document.getElementById('show_r_pago_info');

    btnPago?.classList.toggle('d-none', !(estado === 'pendiente' || estado === 'vencido'));
    btnEdit?.classList.toggle('d-none', estado !== 'pagado');
    
    if (estado === 'pagado') {
        infoPago.style.display = 'block';
        document.getElementById('show_r_pago_fecha').value = card.dataset.pagoFecha;
        document.getElementById('show_r_pago_monto').value = '$' + parseFloat(card.dataset.pagoMonto || 0).toFixed(2);
        document.getElementById('show_r_folio').value = card.dataset.pagoFolio;
        document.getElementById('show_r_pago_forma').value = card.dataset.pagoForma;
    } else {
        infoPago.style.display = 'none';
    }
});
</script>