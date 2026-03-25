<div class="modal fade" id="editConcesionModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-muted"></i>Editar Concesión
                    </h5>
                    <p class="text-muted small mb-0">Modifica los datos de la concesión</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editConcesionForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" id="edit_id_concesion">

                <div class="modal-body pt-3">

                    {{-- ── SECCIÓN 1: Lote y Titular ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Datos generales</span>
                        <div class="row g-3 mt-1">

                            {{-- LOTE --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>Lote
                                </label>

                                <div class="input-group">
                                    <select id="edit_lote" name="id_lote" required>
                                        <option value=""></option>
                                        @foreach ($lotes as $lote)
                                            <option value="{{ $lote->id_lote }}">
                                                Lote {{ $lote->numero }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="button" class="input-group-text" id="btnLoteEdit">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- TITULAR --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person me-1 text-muted"></i>Titular
                                </label>

                                <div class="input-group">
                                    <select id="edit_titular" name="id_titular" required>
                                        <option value=""></option>
                                        @foreach ($titulares as $titular)
                                            <option value="{{ $titular->id_titular }}">
                                                {{ $titular->familia }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="button" class="input-group-text" id="btnTitularEdit">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- TIPO --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Tipo de concesión</span>
                        <div class="row g-3 mt-1">

                            <div class="col-12">
                                <input type="hidden" id="edit_tipo" name="tipo">
                                <div class="d-flex gap-4">

                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="tipo"
                                               id="edit_tipo_temporal"
                                               value="temporal">
                                        <label class="form-check-label fw-semibold" for="edit_tipo_temporal">
                                            <i class="bi bi-hourglass-split me-1 text-muted"></i>
                                            Temporal
                                            <small class="text-muted fw-normal">(7 años)</small>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="tipo"
                                               id="edit_tipo_perpetuidad"
                                               value="perpetuidad">
                                        <label class="form-check-label fw-semibold" for="edit_tipo_perpetuidad">
                                            <i class="bi bi-infinity me-1 text-muted"></i>
                                            Perpetuidad
                                            <small class="text-muted fw-normal">(indefinida)</small>
                                        </label>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CONCESIÓN --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Concesión</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-bookmarks me-1 text-muted"></i>Uso funerario
                                </label>
                                <select id="edit_uso" name="id_uso_funerario" class="form-select" required>
                                    @foreach ($usos as $uso)
                                        <option value="{{ $uso->id_uso_funerario }}">
                                            {{ $uso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event me-1 text-muted"></i>Fecha inicio
                                </label>
                                <input type="date"
                                       id="edit_fecha"
                                       name="fecha_inicio"
                                       class="form-control"
                                       required>
                            </div>

                        </div>
                    </div>

                    {{-- REFRENDO --}}
                    <div class="section-block mb-1">
                        <span class="section-label">Refrendo y observaciones</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-cash-coin me-1 text-muted"></i>Monto
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted">$</span>
                                    <input type="number"
                                        step="0.01"
                                        min="0"
                                        id="edit_monto"
                                        name="monto"
                                        class="form-control"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-x me-1 text-muted"></i>Fecha límite de pago
                                </label>
                                <input type="date"
                                    id="edit_fecha_limite"
                                    name="fecha_limite_pago"
                                    class="form-control">
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-chat-left-text me-1 text-muted"></i>Observaciones
                                </label>

                                <textarea id="edit_observaciones"
                                          name="observaciones"
                                          rows="2"
                                          class="form-control"
                                          placeholder="Notas adicionales..."></textarea>

                            </div>

                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn bg-base text-white px-4">
                        <i class="bi bi-check2-circle me-1"></i>Actualizar concesión
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
let formSubmitted = false;

document.getElementById('editConcesionForm').addEventListener('submit', () => {
    formSubmitted = true;
});

document.getElementById('editConcesionModal').addEventListener('hidden.bs.modal', function () {
    if (!formSubmitted) {
        window.location.href = "{{ route('concesiones.index') }}";
    }

    formSubmitted = false;

    if (tsTitularEdit) tsTitularEdit.clear();
    if (tsLoteEdit) tsLoteEdit.clear();
});

let tsTitularEdit = null;
let tsLoteEdit = null;

document.getElementById('editConcesionModal').addEventListener('shown.bs.modal', function () {

    const tsConfig = {
        openOnFocus: false,
        create: false,
        sortField: { field: "text", direction: "asc" },
        allowEmptyOption: true,
        maxOptions: 10,
        onInitialize: function() {
            // 👇 mismo hack que ya usas y funciona
            this.wrapper.style.setProperty('width', '85%', 'important');
        }
    };

    // TITULAR
    if (!tsTitularEdit) {
        tsTitularEdit = new TomSelect('#edit_titular', {
            ...tsConfig,
            placeholder: "Buscar titular..."
        });

        document.getElementById('btnTitularEdit').addEventListener('click', () => {
            tsTitularEdit.open();
        });
    }

    // LOTE
    if (!tsLoteEdit) {
        tsLoteEdit = new TomSelect('#edit_lote', {
            ...tsConfig,
            placeholder: "Buscar lote..."
        });

        document.getElementById('btnLoteEdit').addEventListener('click', () => {
            tsLoteEdit.open();
        });
    }

    // 🔥 setear valores
    if (concesionActual && concesionActual.id_titular) {
        tsTitularEdit.setValue(concesionActual.id_titular, true);
    }

    if (concesionActual && concesionActual.id_lote) {
        tsLoteEdit.setValue(concesionActual.id_lote, true);
    }

    // 🔥 refrescar estado (igual que create)
    tsTitularEdit.refreshState();
    tsLoteEdit.refreshState();
});
</script>