<div class="modal fade" id="createEspacioFisicoModal" tabindex="-1"
     aria-labelledby="createEspacioFisicoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="createEspacioFisicoModalLabel">
                        <i class="bi bi-plus-circle me-2 text-muted"></i>Nuevo Espacio Físico
                    </h5>
                    <p class="text-muted small mb-0">Completa los datos para registrar el espacio</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('espacios_fisicos.store') }}" method="POST">
                @csrf

                <div class="modal-body pt-3">

                    {{-- ── SECCIÓN 1: Ubicación ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Ubicación</span>
                        <div class="row g-3 mt-1">

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-grid me-1 text-muted"></i>Sección
                                </label>
                                <select name="id_seccion" id="id_seccion"
                                        class="form-select @error('id_seccion') is-invalid @enderror"
                                        required>
                                    <option value="">Seleccione la sección...</option>
                                    @foreach ($secciones as $seccion)
                                        <option value="{{ $seccion->id_seccion }}"
                                            {{ old('id_seccion') == $seccion->id_seccion ? 'selected' : '' }}>
                                            {{ $seccion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_seccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── SECCIÓN 2: Datos del espacio ── --}}
                    <div class="section-block mb-1">
                        <span class="section-label">Datos del espacio</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-bookmarks me-1 text-muted"></i>Tipo
                                </label>
                                <select name="id_tipo_espacio_fisico" id="id_tipo_espacio_fisico"
                                        class="form-select @error('id_tipo_espacio_fisico') is-invalid @enderror"
                                        required>
                                    <option value="">Seleccione un tipo...</option>
                                    @foreach ($tiposEspacioFisico as $tipo)
                                        <option value="{{ $tipo->id_tipo_espacio_fisico }}"
                                            {{ old('id_tipo_espacio_fisico') == $tipo->id_tipo_espacio_fisico ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_tipo_espacio_fisico')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-7">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1 text-muted"></i>Nombre del espacio
                                </label>
                                <input type="text" name="nombre" id="nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre') }}"
                                       placeholder="Ej. Área A"
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-chat-left-text me-1 text-muted"></i>Descripción
                                </label>
                                <textarea name="descripcion" id="descripcion" rows="2"
                                          class="form-control @error('descripcion') is-invalid @enderror"
                                          placeholder="Descripción opcional del espacio...">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                </div>

                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn bg-base text-white px-4">
                        <i class="bi bi-check2-circle me-1"></i>Guardar espacio
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>