<div class="modal fade" id="editBeneficiarioModal" tabindex="-1" aria-labelledby="editBeneficiarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="editBeneficiarioModalLabel">
                        <i class="bi bi-pencil-square me-2 text-muted"></i>Editar Beneficiario
                    </h5>
                    <p class="text-muted small mb-0">Modifica la información del beneficiario</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editBeneficiarioForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" id="edit_id" name="id_beneficiario">
                <input type="hidden" id="edit_orden" name="orden">
                <input type="hidden" id="edit_id_titular_hidden">
                <div class="modal-body pt-3">


                    {{-- SECCIÓN 0: Titular (modal edit) --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Titular asociado</span>
                        <div class="row g-3 mt-1">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-check me-1 text-muted"></i>Cambiar Titular <span class="text-danger">*</span>
                                </label>
                                <select id="select-titular-edit" name="id_titular" class="form-select" required>
                                    <option value=""></option>
                                    @foreach($titulares as $titular)
                                        <option value="{{ $titular->id_titular }}"
                                                data-domicilio="{{ $titular->domicilio }}"
                                                data-colonia="{{ $titular->colonia }}"
                                                data-cp="{{ $titular->codigo_postal }}"
                                                data-municipio="{{ $titular->municipio }}"
                                                data-estado="{{ $titular->estado }}">
                                            {{ $titular->familia }} - {{ $titular->colonia ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 1: Identificación (Igual al Create) --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Identificación y Contacto</span>
                        <div class="row g-3 mt-1">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person me-1 text-muted"></i>Nombre del beneficiario <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_nombre" name="nombre" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-telephone me-1 text-muted"></i>Teléfono
                                </label>
                                <input type="text" id="edit_telefono" name="telefono" class="form-control">
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 2: Domicilio (Igual al Create) --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Domicilio</span>
                        <div class="row g-3 mt-1">
                            <div class="col-md-7">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-house me-1 text-muted"></i>Calle y número <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_domicilio" name="domicilio" class="form-control" required>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-signpost me-1 text-muted"></i>Colonia <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_colonia" name="colonia" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-mailbox me-1 text-muted"></i>C.P. <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_cp" name="codigo_postal" class="form-control" maxlength="5" required>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-building me-1 text-muted"></i>Municipio <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_municipio" name="municipio" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-map me-1 text-muted"></i>Estado <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_estado" name="estado" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    {{-- DOCUMENTOS --}}
                    @if($tiposDocumento->isNotEmpty())
                    <div class="section-block mb-3">
                        <span class="section-label">Documentos</span>
                        <div class="row g-3 mt-1">
                            @foreach($tiposDocumento as $tipo)
                            <div class="{{ $tiposDocumento->count() === 1 ? 'col-12' : 'col-md-6' }}">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-semibold mb-0">
                                        <i class="bi bi-file-earmark me-1 text-muted"></i>{{ $tipo->nombre }}
                                    </label>
                                    <span id="edit_doc_link_{{ $tipo->id_tipo_documento }}"></span>
                                </div>
                                <input type="file" name="documentos[{{ $tipo->id_tipo_documento }}][archivo]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <input type="hidden" name="documentos[{{ $tipo->id_tipo_documento }}][id_tipo_documento]" value="{{ $tipo->id_tipo_documento }}">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn bg-base text-white px-4">Actualizar beneficiario</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let formSubmitted = false;
let editModalOpened = false;

document.getElementById('editBeneficiarioForm').addEventListener('submit', () => {
    formSubmitted = true;
});

document.getElementById('editBeneficiarioModal')
    .addEventListener('hidden.bs.modal', function () {
        if (!formSubmitted && editModalOpened) {
            window.location.href = "/beneficiarios";
        }
        editModalOpened = false;
    });

window.abrirModalEditBeneficiario = function() {
    editModalOpened = true;
    new bootstrap.Modal(document.getElementById('editBeneficiarioModal')).show();
};

document.addEventListener('DOMContentLoaded', function () {

    const tsConfig = {
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: "Buscar titular...",
        allowEmptyOption: true,
        maxOptions: null,
    };

    const selectEdit = new TomSelect("#select-titular-edit", tsConfig);

    const modalEdit = document.getElementById('editBeneficiarioModal');
    if (modalEdit) {
        modalEdit.addEventListener('shown.bs.modal', function () {
            if (typeof beneficiarioActual !== 'undefined' && beneficiarioActual.id_titular) {
                selectEdit.setValue(beneficiarioActual.id_titular, true);
            }
            selectEdit.focus();
        });
    }
});
</script>