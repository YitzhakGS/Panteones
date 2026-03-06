<div class="modal fade"
     id="editEspacioFisicoModal"
     tabindex="-1"
     aria-labelledby="editEspacioFisicoModalLabel"
     aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="editEspacioFisicoModalLabel">
                        <i class="bi bi-pencil-square me-2 text-muted"></i>Editar Espacio Físico
                    </h5>
                    <p class="text-muted small mb-0">Modifica la información del espacio</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editEspacioFisicoForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden"
                       name="id_espacio_fisico"
                       id="edit_id_espacio_fisico">

                <div class="modal-body pt-3">

                    {{-- ── SECCIÓN 1: Ubicación ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Ubicación</span>

                        <div class="row g-3 mt-1">

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-grid me-1 text-muted"></i>Sección / Cuadrilla
                                </label>

                                <select name="id_cuadrilla"
                                        id="edit_id_cuadrilla"
                                        class="form-select @error('id_cuadrilla') is-invalid @enderror"
                                        required>

                                    <option value="">Seleccione sección y cuadrilla...</option>

                                    @foreach ($cuadrillas as $cuadrilla)
                                        <option value="{{ $cuadrilla->id_cuadrilla }}">
                                            {{ $cuadrilla->seccion->nombre }} — {{ $cuadrilla->nombre }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('id_cuadrilla')
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

                                <select name="id_tipo_espacio_fisico"
                                        id="edit_id_tipo_espacio_fisico"
                                        class="form-select @error('id_tipo_espacio_fisico') is-invalid @enderror"
                                        required>

                                    <option value="">Seleccione un tipo...</option>

                                    @foreach ($tiposEspacioFisico as $tipo)
                                        <option value="{{ $tipo->id_tipo_espacio_fisico }}">
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

                                <input type="text"
                                       name="nombre"
                                       id="edit_nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
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

                                <textarea name="descripcion"
                                          id="edit_descripcion"
                                          rows="2"
                                          class="form-control @error('descripcion') is-invalid @enderror"
                                          placeholder="Descripción opcional del espacio..."></textarea>

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
                        <i class="bi bi-check2-circle me-1"></i>Actualizar espacio
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>