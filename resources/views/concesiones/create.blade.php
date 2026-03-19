<div class="modal fade" id="createConcesionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-muted"></i>Nueva Concesión
                    </h5>
                    <p class="text-muted small mb-0">Completa los datos para registrar la concesión</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('concesiones.store') }}" method="POST">
                @csrf

                <div class="modal-body pt-3">

                    {{-- ── SECCIÓN 1: Lote y Titular ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Datos generales</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>Lote
                                </label>
                                <select name="id_lote"
                                        class="form-select @error('id_lote') is-invalid @enderror"
                                        required>
                                    <option value="">Seleccione un lote...</option>
                                    @foreach ($lotes as $lote)
                                        <option value="{{ $lote->id_lote }}">
                                            Lote {{ $lote->numero }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_lote')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person me-1 text-muted"></i>Titular
                                </label>
                                <select name="id_titular"
                                        class="form-select @error('id_titular') is-invalid @enderror"
                                        required>
                                    <option value="">Seleccione un titular...</option>
                                    @foreach ($titulares as $titular)
                                        <option value="{{ $titular->id_titular }}">
                                            {{ $titular->familia }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_titular')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── SECCIÓN 2: Tipo de concesión ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Tipo de concesión</span>
                        <div class="row g-3 mt-1">

                            <div class="col-12">
                                <div class="d-flex gap-4">

                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="tipo"
                                               id="tipo_temporal"
                                               value="temporal"
                                               checked>
                                        <label class="form-check-label fw-semibold" for="tipo_temporal">
                                            <i class="bi bi-hourglass-split me-1 text-muted"></i>
                                            Temporal
                                            <small class="text-muted fw-normal">(7 años)</small>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="tipo"
                                               id="tipo_perpetuidad"
                                               value="perpetuidad">
                                        <label class="form-check-label fw-semibold" for="tipo_perpetuidad">
                                            <i class="bi bi-infinity me-1 text-muted"></i>
                                            Perpetuidad
                                            <small class="text-muted fw-normal">(indefinida)</small>
                                        </label>
                                    </div>

                                </div>
                                @error('tipo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── SECCIÓN 3: Uso funerario y Fecha ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Concesión</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-bookmarks me-1 text-muted"></i>Uso funerario
                                </label>
                                <select name="id_uso_funerario"
                                        class="form-select @error('id_uso_funerario') is-invalid @enderror"
                                        required>
                                    <option value="">Seleccione el uso...</option>
                                    @foreach ($usos as $uso)
                                        <option value="{{ $uso->id_uso_funerario }}">
                                            {{ $uso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_uso_funerario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event me-1 text-muted"></i>Fecha de inicio
                                </label>
                                <input type="date"
                                       name="fecha_inicio"
                                       class="form-control @error('fecha_inicio') is-invalid @enderror"
                                       value="{{ now()->format('Y-m-d') }}"
                                       required>
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── SECCIÓN 4: Refrendo + Observaciones ── --}}
                    <div class="section-block mb-1">
                        <span class="section-label">Refrendo inicial y observaciones</span>
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
                                       name="fecha_limite_pago"
                                       class="form-control @error('fecha_limite_pago') is-invalid @enderror">
                                <small class="text-muted">Opcional — se calcula automáticamente</small>
                                @error('fecha_limite_pago')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-chat-left-text me-1 text-muted"></i>Observaciones
                                </label>
                                <textarea name="observaciones"
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
                        <i class="bi bi-x me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn bg-base text-white px-4">
                        <i class="bi bi-check2-circle me-1"></i>Guardar concesión
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('crear_refrendo').addEventListener('change', function () {
        const fields = document.getElementById('refrendo-fields');
        const inputMonto = document.getElementById('input_monto');
        
        if(this.checked) {
            fields.style.display = 'block';
            inputMonto.setAttribute('required', 'required');
        } else {
            fields.style.display = 'none';
            inputMonto.removeAttribute('required');
        }
    });
</script>