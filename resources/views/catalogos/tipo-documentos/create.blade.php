<div class="modal fade" id="createTipoDocumentoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-muted"></i>
                        Nuevo Tipo de Documento
                    </h5>
                    <p class="text-muted small mb-0">Registra un nuevo tipo de documento</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('tipo-documentos.store') }}" method="POST">
                @csrf
                <div class="modal-body pt-3">
                    <div class="section-block">
                        <span class="section-label">Información</span>
                        <div class="row g-3 mt-1">

                            <div class="col-12">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text" name="nombre" class="form-control"
                                    placeholder="Ej. INE" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3"
                                    placeholder="Descripción opcional"></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Aplica para</label>
                                <select name="modelos[]" id="select-crear-modelos"
                                    class="form-control" multiple>
                                    <option value="App\Models\Titular">Titular</option>
                                    <option value="App\Models\Beneficiario">Beneficiario</option>
                                </select>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-light border"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn bg-base text-white px-4">
                                    Guardar</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>