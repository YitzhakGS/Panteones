<div class="modal fade" id="pagoRefrendoModal" tabindex="-1"
     aria-labelledby="pagoRefrendoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="pagoRefrendoModalLabel">
                        <i class="bi bi-cash-stack me-2 text-muted"></i>Registrar Pago
                    </h5>
                    <p class="text-muted small mb-0">Completa los datos del pago del refrendo</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('pagos.store') }}" method="POST">
                @csrf
                <input type="hidden" id="pago_id_refrendo" name="id_refrendo">

                <div class="modal-body pt-3">

                    {{-- SECCIÓN 1: Referencia --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Concesión</span>
                        <div class="row g-3 mt-1">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>Lote
                                </label>
                                <input type="text" id="pago_lote"
                                       class="form-control bg-light border-0" readonly>
                            </div>
                            <div class="col-md-9">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person me-1 text-muted"></i>Titular
                                </label>
                                <input type="text" id="pago_titular"
                                       class="form-control bg-light border-0" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 2: Datos del pago --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Datos del pago</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event me-1 text-muted"></i>Fecha de pago
                                </label>
                                <input type="date"
                                       name="fecha_pago"
                                       id="pago_fecha"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-cash-coin me-1 text-muted"></i>Monto
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted">$</span>
                                    <input type="number"
                                           step="0.01"
                                           min="0.01"
                                           name="monto"
                                           id="pago_monto"
                                           class="form-control"
                                           placeholder="0.00"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-credit-card me-1 text-muted"></i>Forma de pago
                                </label>
                                <select name="forma_pago" class="form-select">
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-receipt me-1 text-muted"></i>Folio de ticket
                                </label>
                                <input type="text"
                                       name="folio_ticket"
                                       class="form-control"
                                       placeholder="Opcional">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-chat-left-text me-1 text-muted"></i>Observaciones
                                </label>
                                <input type="text"
                                       name="observaciones"
                                       class="form-control"
                                       placeholder="Opcional">
                            </div>

                        </div>
                    </div>

                </div>

                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn bg-base text-white px-4">
                        <i class="bi bi-check2-circle me-1"></i>Registrar pago
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
let formSubmitted = false;

document.getElementById('pagoRefrendoModal').querySelector('form').addEventListener('submit', () => {
    formSubmitted = true;
});

document.getElementById('pagoRefrendoModal').addEventListener('hidden.bs.modal', function () {
    if (!formSubmitted) {
        window.location.href = "{{ route('refrendos.index') }}";
    }
});
</script>