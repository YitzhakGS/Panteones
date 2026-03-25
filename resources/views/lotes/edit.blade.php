{{-- MODAL EDITAR --}}
<div class="modal fade" id="editLoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-muted"></i>Editar Lote
                    </h5>
                    <p class="text-muted small mb-0">Modifica la información técnica y de ubicación del lote</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editLoteForm" method="POST" action="">
                @csrf
                @method('PUT')

                <input type="hidden" id="current_espacio_id">

                <div class="modal-body pt-3">

                    {{-- ── SECCIÓN 1: Identificación ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Identificación</span>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-hash me-1 text-muted"></i>Número de lote
                                </label>
                                <input type="text" name="numero" class="form-control"
                                       placeholder="Ej. 042" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-rulers me-1 text-muted"></i>Superficie (m²)
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="metros_cuadrados"
                                           id="edit_metros_cuadrados" class="form-control bg-light" readonly>
                                    <span class="input-group-text text-muted">m²</span>
                                </div>
                                <small class="text-muted" style="font-size: 0.75rem;">Calculado automáticamente</small>
                            </div>
                        </div>
                    </div>

                    {{-- ── SECCIÓN 2: Medidas ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Medidas (metros)</span>
                        <div class="row g-3 mt-1">
                            @foreach(['norte' => 'up', 'sur' => 'down', 'oriente' => 'right', 'poniente' => 'left'] as $dir => $icon)
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-arrow-{{ $icon }} me-1"></i>{{ ucfirst($dir) }}
                                </label>
                                <input type="number" step="0.01" name="med_{{ $dir }}"
                                       class="form-control edit-measure-input" placeholder="0.00">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── SECCIÓN 3: Colindancias ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Colindancias</span>
                        <div class="row g-3 mt-1">
                            @foreach(['norte', 'sur', 'oriente', 'poniente'] as $dir)
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-signpost-split me-1"></i>{{ ucfirst($dir) }}
                                </label>
                                <input type="text" name="col_{{ $dir }}" class="form-control"
                                       placeholder="Colindancia {{ ucfirst($dir) }}">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── SECCIÓN 4: Ubicación Geográfica ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Ubicación del lote</span>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-layers me-1 text-muted"></i>Sección General
                                </label>
                                <select id="edit-select-seccion-ajax" class="form-select border-primary">
                                    <option value="">-- Seleccione Sección --</option>
                                    @foreach ($secciones as $seccion)
                                        <option value="{{ $seccion->id_seccion }}">{{ $seccion->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>Área / Espacio Físico
                                </label>
                                <select name="id_espacio_fisico" id="edit-select-espacio-ajax"
                                        class="form-select" required>
                                    <option value="">Primero elija una sección...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ── SECCIÓN 5: Referencias ── --}}
                    <div class="section-block mb-1">
                        <span class="section-label">Referencias adicionales</span>
                        <div class="mt-1">
                            <textarea name="referencias" rows="2" class="form-control"
                                      placeholder="Notas, ubicación visual, referencias específicas..."></textarea>
                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn bg-base text-white px-4">
                        <i class="bi bi-check2-circle me-1"></i>Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>

</script>