<div class="modal fade" id="editTipoDocumentoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-muted"></i>
                        Editar Tipo de Documento
                    </h5>
                    <p class="text-muted small mb-0">Modifica la información</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editTipoDocumentoForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body pt-3">
                    <div class="section-block">
                        <span class="section-label">Información</span>
                        <div class="row g-3 mt-1">

                            <div class="col-12">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text" name="nombre" id="edit_nombre"
                                    class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripción</label>
                                <textarea name="descripcion" id="edit_descripcion"
                                    class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Aplica para</label>
                                <select name="modelos[]" id="select-editar-modelos"
                                    class="form-control" multiple>
                                    <option value="App\Models\Titular">Titular</option>
                                    <option value="App\Models\Beneficiario">Beneficiario</option>
                                </select>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-light border"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn bg-base text-white px-4">
                                    Actualizar</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>