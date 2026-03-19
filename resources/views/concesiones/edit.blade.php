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

                    {{-- DATOS GENERALES --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Datos generales</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>Lote
                                </label>
                                <select id="edit_lote" name="id_lote" class="form-select" required>
                                    <option value="">Seleccione un lote...</option>
                                    @foreach ($lotes as $lote)
                                        <option value="{{ $lote->id_lote }}">
                                            Lote {{ $lote->numero }}
                                        </option>
                                    @endforeach
                                </select>

                                <select id="edit_titular" name="id_titular" class="form-select" required>
                                    <option value="">Seleccione un titular...</option>
                                    @foreach ($titulares as $titular)
                                        <option value="{{ $titular->id_titular }}">
                                            {{ $titular->familia }}
                                        </option>
                                    @endforeach
                                </select>
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

                    {{-- OBSERVACIONES --}}
                    <div class="section-block mb-1">
                        <span class="section-label">Observaciones</span>
                        <div class="row g-3 mt-1">
                            <div class="col-12">
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
});
</script>