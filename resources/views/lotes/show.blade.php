<div class="modal fade" id="showLoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="showLoteModalLabel">
                        <i class="bi bi-grid-3x3-gap me-2 text-muted"></i>Detalle del Lote
                    </h5>
                    <p class="text-muted small mb-0">Información registrada del lote</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body pt-3">

                {{-- ── SECCIÓN 1: Identificación + Ubicación ── --}}
                <div class="section-block mb-3">
                    <span class="section-label">Identificación</span>
                    <div class="row g-3 mt-1">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-hash me-1 text-muted"></i>Número de lote
                            </label>
                            <div class="lote-show-badge">
                                <span id="show_lote_numero">—</span>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-box me-1 text-muted"></i>Superficie
                            </label>
                            <div class="input-group">
                                <span type="text" id="show_lote_superficie"
                                       class="form-control bg-light border-0 fw-bold" readonly></span>
                                <span class="input-group-text bg-light border-0 text-muted">m²</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-pin-map-fill me-1 text-muted"></i>Ubicación actual
                            </label>
                            <span type="text" id="show_lote_ubicacion"
                                   class="form-control bg-light border-0" readonly
                                   placeholder="Sin ubicación asignada"></span>
                        </div>

                    </div>
                </div>

                {{-- ── SECCIÓN 2: Medidas y Colindancias ── --}}
                <div class="section-block mb-3">
                    <span class="section-label">Medidas y Colindancias</span>
                    <div class="row g-3 mt-1">

                        {{-- Tabla brújula --}}
                        <div class="col-12">
                            <div class="lote-compass-table">

                                {{-- Encabezados --}}
                                <div class="lct-header">
                                    <span></span>
                                    <span><i class="bi bi-rulers me-1"></i>Medida</span>
                                    <span><i class="bi bi-diagram-3 me-1"></i>Colindancia</span>
                                </div>

                                {{-- Norte --}}
                                <div class="lct-row">
                                    <div class="lct-dir lct-n">
                                        <i class="bi bi-arrow-up"></i> Norte
                                    </div>
                                    <div class="lct-med">
                                        <span id="show_med_norte">—</span>
                                    </div>
                                    <div class="lct-col">
                                        <span id="show_col_norte">—</span>
                                    </div>
                                </div>

                                {{-- Sur --}}
                                <div class="lct-row">
                                    <div class="lct-dir lct-s">
                                        <i class="bi bi-arrow-down"></i> Sur
                                    </div>
                                    <div class="lct-med">
                                        <span id="show_med_sur">—</span>
                                    </div>
                                    <div class="lct-col">
                                        <span id="show_col_sur">—</span>
                                    </div>
                                </div>

                                {{-- Oriente --}}
                                <div class="lct-row">
                                    <div class="lct-dir lct-o">
                                        <i class="bi bi-arrow-right"></i> Oriente
                                    </div>
                                    <div class="lct-med">
                                        <span id="show_med_oriente">—</span>
                                    </div>
                                    <div class="lct-col">
                                        <span id="show_col_oriente">—</span>
                                    </div>
                                </div>

                                {{-- Poniente --}}
                                <div class="lct-row lct-row-last">
                                    <div class="lct-dir lct-p">
                                        <i class="bi bi-arrow-left"></i> Poniente
                                    </div>
                                    <div class="lct-med">
                                        <span id="show_med_poniente">—</span>
                                    </div>
                                    <div class="lct-col">
                                        <span id="show_col_poniente">—</span>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── SECCIÓN 3: Referencias + Botón ── --}}
                <div class="section-block mb-1">
                    <span class="section-label">Referencias</span>
                    <div class="row g-3 mt-1 align-items-end">

                        <div class="col-md-10">
                            <span id="show_lote_referencias" rows="2"
                                      class="form-control bg-light border-0" readonly
                                      placeholder="Sin referencias registradas..."></span>
                        </div>

                        <div class="col-md-2 d-flex justify-content-end">
                            <button type="button" class="btn btn-light border w-100" data-bs-dismiss="modal">
                                <i class="bi bi-x me-1"></i>Cerrar
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* ── Badge número de lote ── */
.lote-show-badge {
    height: 38px;
    background: linear-gradient(135deg, #1e3a5f, #2d5a8e);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: .5px;
}

/* ── Tabla brújula ── */
.lote-compass-table {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #e9eef4;
}

.lct-header {
    display: grid;
    grid-template-columns: 130px 1fr 2fr;
    background: #f1f5f9;
    padding: 8px 16px;
    gap: 12px;
}

.lct-header span {
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #94a3b8;
}

.lct-row {
    display: grid;
    grid-template-columns: 130px 1fr 2fr;
    border-top: 1px solid #f1f5f9;
    gap: 12px;
    transition: background .15s;
}

.lct-row:hover { background: #f8fafc; }
.lct-row-last  { border-radius: 0 0 10px 10px; }

.lct-dir {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    font-size: .82rem;
    font-weight: 700;
    color: #475569;
}

.lct-n i { color: #3b82f6; }
.lct-s i { color: #f59e0b; }
.lct-o i { color: #10b981; }
.lct-p i { color: #8b5cf6; }

.lct-med, .lct-col {
    display: flex;
    align-items: center;
    padding: 10px 0;
    font-size: .9rem;
    color: #334155;
}

.lct-med span {
    font-weight: 700;
    color: #1d4ed8;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    padding: 2px 10px;
    font-size: .85rem;
}

.lct-col span {
    color: #475569;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 2px 10px;
    font-size: .85rem;
}
</style>