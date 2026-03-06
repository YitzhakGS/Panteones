<div class="modal fade" id="editCuadrillaModal" tabindex="-1"
     aria-labelledby="editCuadrillaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="editCuadrillaModalLabel">
                        <i class="bi bi-pencil-square me-2 text-muted"></i>Editar Cuadrilla
                    </h5>
                    <p class="text-muted small mb-0">Modifica los datos de la cuadrilla</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editCuadrillaForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden"
                       name="id_cuadrilla"
                       id="edit_id_cuadrilla">

                <div class="modal-body pt-3">

                    <div class="section-block mb-1">
                        <span class="section-label">Datos de la cuadrilla</span>

                        <div class="row g-3 mt-1">

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-grid me-1 text-muted"></i>Sección
                                </label>

                                <select name="id_seccion"
                                        id="edit_id_seccion"
                                        class="form-select @error('id_seccion') is-invalid @enderror"
                                        required>

                                    <option value="">Seleccione una sección...</option>

                                    @foreach ($secciones as $seccion)
                                        <option value="{{ $seccion->id_seccion }}">
                                            {{ $seccion->nombre }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('id_seccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1 text-muted"></i>Nombre de la cuadrilla
                                </label>

                                <input type="text"
                                       name="nombre"
                                       id="edit_nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       placeholder="Ej. Cuadrilla 1"
                                       required>

                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 pt-1">
                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                                    <i class="bi bi-x me-1"></i>Cancelar
                                </button>

                                <button type="submit" class="btn bg-base text-white px-4">
                                    <i class="bi bi-check2-circle me-1"></i>Actualizar cuadrilla
                                </button>
                            </div>

                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>