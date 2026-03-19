<div class="modal fade" id="editTitularModal" tabindex="-1"
     aria-labelledby="editTitularModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="editTitularModalLabel">
                        <i class="bi bi-pencil-square me-2 text-muted"></i>Editar Titular
                    </h5>
                    <p class="text-muted small mb-0">Modifica la información del titular</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editTitularForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" id="edit_id" name="id_titular">

                <div class="modal-body pt-3">

                    {{-- SECCIÓN 1: Identificación --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Identificación</span>
                        <div class="row g-3 mt-1">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-people me-1 text-muted"></i>
                                    Familia / Titular <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_familia" name="familia"
                                    class="form-control"
                                    placeholder="Ej. García Pérez" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-telephone me-1 text-muted"></i>
                                    Teléfono <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_telefono" name="telefono"
                                    class="form-control" placeholder="10 dígitos" required>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 2: Domicilio --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Domicilio</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-7">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-house me-1 text-muted"></i>
                                    Calle y número <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_domicilio" name="domicilio"
                                    class="form-control"
                                    placeholder="Calle, número, manzana, lote" required>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-signpost me-1 text-muted"></i>
                                    Colonia <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_colonia" name="colonia"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-mailbox me-1 text-muted"></i>
                                    C.P. <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_cp" name="codigo_postal"
                                    class="form-control" maxlength="5"
                                    placeholder="00000" required>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-building me-1 text-muted"></i>
                                    Municipio <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_municipio" name="municipio"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-map me-1 text-muted"></i>
                                    Estado <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_estado" name="estado"
                                    class="form-control" placeholder="Ej. Hidalgo" required>
                            </div>

                        </div>
                    </div>

                    {{-- SECCIÓN 4: Documentos --}}
                    @if($tiposDocumentoTitular->isNotEmpty())
                    <div class="section-block mb-3">
                        <span class="section-label">Documentos <span class="text-muted fw-normal">(opcional)</span></span>
                        <div class="row g-3 mt-1">
                            @foreach($tiposDocumentoTitular as $tipo)
                            <div class="{{ $tiposDocumentoTitular->count() === 1 ? 'col-12' : 'col-md-6' }}">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label class="form-label fw-semibold mb-0">
                                        <i class="bi bi-file-earmark me-1 text-muted"></i>
                                        {{ $tipo->nombre }}
                                    </label>
                                    {{-- El enlace "Ver actual" se inyecta dinámicamente --}}
                                    <span id="edit_doc_link_{{ $tipo->id_tipo_documento }}"></span>
                                </div>
                                <input type="file"
                                    name="documentos[{{ $tipo->id_tipo_documento }}][archivo]"
                                    class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                                <input type="hidden"
                                    name="documentos[{{ $tipo->id_tipo_documento }}][id_tipo_documento]"
                                    value="{{ $tipo->id_tipo_documento }}">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Botones --}}
                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            <i class="bi bi-x me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn bg-base text-white px-4">
                            <i class="bi bi-check2-circle me-1"></i>Actualizar titular
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<script>
// En edit.blade.php - reemplaza el listener actual
let formSubmitted = false;
let editModalOpened = false; // Nueva bandera

document.getElementById('editTitularForm').addEventListener('submit', () => {
    formSubmitted = true;
});

document.getElementById('editTitularModal')
    .addEventListener('hidden.bs.modal', function () {
        if (!formSubmitted && editModalOpened) {
            window.location.href = "{{ route('titulares.index') }}";
        }
        editModalOpened = false;
    });

// Expón la bandera para que show.blade.php la active
window.abrirModalEdit = function() {
    editModalOpened = true;
    new bootstrap.Modal(document.getElementById('editTitularModal')).show();
};


document.addEventListener('DOMContentLoaded', function () {

    const showModal = document.getElementById('showTitularModal');

    if (!showModal) return;

    showModal.addEventListener('show.bs.modal', function (event) {

        const card = event.relatedTarget;
        const documentos = JSON.parse(card.dataset.documentos || '[]');
        titularActual = { documentos: documentos};

        documentos.forEach(doc => {

            const linkContainer = document.getElementById('edit_doc_link_' + doc.id_tipo_documento);

            if (!linkContainer) return;

            linkContainer.innerHTML = `
                <a href="/documentos/${doc.id}" target="_blank"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-eye"></i> Ver actual
                </a>
            `;
        });

    });

});
</script>
