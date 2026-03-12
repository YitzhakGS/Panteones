<div class="modal fade" id="createSeccionModal" tabindex="-1"
     aria-labelledby="createSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="createSeccionModalLabel">
                        <i class="bi bi-plus-circle me-2 text-muted"></i>Nueva Sección
                    </h5>
                    <p class="text-muted small mb-0">Ingresa el nombre para registrar la sección</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('secciones.store') }}" method="POST">
                @csrf

                <div class="modal-body pt-3">

                    <div class="section-block mb-1">
                        <span class="section-label">Identificación</span>
                        <div class="row g-3 mt-1">

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1 text-muted"></i>Nombre de la sección
                                </label>
                                <input type="text" id="nombre" name="nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre') }}"
                                       placeholder="Ej. Sección Norte"
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
                                    <i class="bi bi-check2-circle me-1"></i>Guardar sección
                                </button>
                            </div>

                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>