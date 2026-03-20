<div class="modal fade" id="showBeneficiarioModal" tabindex="-1"
     aria-labelledby="showBeneficiarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="showBeneficiarioModalLabel">
                        <i class="bi bi-person-lines-fill me-2 text-muted"></i>Detalle del Beneficiario
                    </h5>
                    <p class="text-muted small mb-0">Información registrada del beneficiario</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <input type="hidden" id="show_id">
            <input type="hidden" id="show_orden"> 
            <div class="modal-body pt-3">

                {{-- SECCIÓN 0: Titular --}}
                <div class="section-block mb-3">
                    <span class="section-label">Titular asociado</span>
                    <div class="row g-2 mt-1">
                        <div class="col-md-12">
                            <p class="text-muted small mb-0">Familia</p>
                            <p id="show_titular" class="fw-semibold mb-0"></p>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 1: Identificación --}}
                <div class="section-block mb-3">
                    <span class="section-label">Identificación y Contacto</span>
                    <div class="row g-2 mt-1">
                        <div class="col-md-8">
                            <p class="text-muted small mb-0">Nombre</p>
                            <p id="show_nombre" class="fw-semibold mb-0"></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-0">Teléfono</p>
                            <p id="show_telefono" class="fw-semibold mb-0"></p>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 2: Domicilio --}}
                <div class="section-block mb-3">
                    <span class="section-label">Domicilio</span>
                    <div class="row g-2 mt-1">
                        <div class="col-md-8">
                            <p class="text-muted small mb-0">Calle y número</p>
                            <p id="show_domicilio" class="fw-semibold mb-0"></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-0">Colonia</p>
                            <p id="show_colonia" class="fw-semibold mb-0"></p>
                        </div>
                        <div class="col-md-5">
                            <p class="text-muted small mb-0">C.P.</p>
                            <p id="show_cp" class="fw-semibold mb-0"></p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted small mb-0">Municipio</p>
                            <p id="show_municipio" class="fw-semibold mb-0"></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-0">Estado</p>
                            <p id="show_estado" class="fw-semibold mb-0"></p>
                        </div>
                    </div>
                </div>

                {{-- DOCUMENTOS --}}
                <div class="section-block mb-3">
                    <span class="section-label">Documentos</span>
                    <div class="row g-3 mt-1" id="show_documentos_container">
                        {{-- JS --}}
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-end gap-2 mt-2">
                    <button type="button" class="btn btn-outline-danger"
                            onclick="confirmarEliminacionBeneficiario()">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                    <button type="button" class="btn bg-base text-white px-4"
                            id="btnEditarBeneficiario">
                        <i class="bi bi-pencil-square me-1"></i>Editar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<form id="deleteBeneficiarioForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@include('beneficiarios.edit')
<script>
function confirmarEliminacionBeneficiario() {
    const id = document.getElementById('show_id').value;

    Swal.fire({
        title: '¿Eliminar beneficiario?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteBeneficiarioForm');
            form.action = `/beneficiarios/${id}`;
            form.submit();
        }
    });
}

// 👇 Todo envuelto en DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {

    const btnEditar = document.getElementById('btnEditarBeneficiario');

    if (!btnEditar) return; // 👈 Seguridad por si no existe

    btnEditar.addEventListener('click', function () {
        const id = document.getElementById('show_id').value;

        const modalShowElement = document.getElementById('showBeneficiarioModal');

        // 👇 getOrCreateInstance en lugar de getInstance (evita null)
        const modalShow = bootstrap.Modal.getOrCreateInstance(modalShowElement);

        modalShowElement.addEventListener('hidden.bs.modal', function () {

            // --- A. Llenamos campos del Edit ---
            const form = document.getElementById('editBeneficiarioForm');
            form.action = `/beneficiarios/${id}`;

            document.getElementById('edit_id').value        = id;
            document.getElementById('edit_nombre').value    = document.getElementById('show_nombre').innerText.trim();
            document.getElementById('edit_domicilio').value = document.getElementById('show_domicilio').innerText.trim();
            document.getElementById('edit_colonia').value   = document.getElementById('show_colonia').innerText.trim();
            document.getElementById('edit_cp').value        = document.getElementById('show_cp').innerText.trim();
            document.getElementById('edit_municipio').value = document.getElementById('show_municipio').innerText.trim();
            document.getElementById('edit_estado').value    = document.getElementById('show_estado').innerText.trim();
            document.getElementById('edit_telefono').value  = document.getElementById('show_telefono').innerText.trim();

            // Guardar el id_titular para que TomSelect lo lea al abrir el modal
            const hiddenTitular = document.getElementById('edit_id_titular_hidden');
            if (hiddenTitular && beneficiarioActual?.id_titular) {
                hiddenTitular.value = beneficiarioActual.id_titular;
            }
            const ordenEl = document.getElementById('show_orden');
            if (ordenEl) document.getElementById('edit_orden').value = ordenEl.textContent;

            // --- B. Documentos ---
            document.querySelectorAll('[id^="edit_doc_link_"]').forEach(el => el.innerHTML = '');

            if (typeof beneficiarioActual !== 'undefined' && beneficiarioActual?.documentos) {
                beneficiarioActual.documentos.forEach(doc => {
                    const span = document.getElementById(`edit_doc_link_${doc.id_tipo_documento}`);
                    if (span) {
                        span.innerHTML = `
                            <a href="/documentos/${doc.id}" target="_blank"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye me-1"></i>Ver actual
                            </a>`;
                    }
                });
            }

            // --- C. Abrimos el Edit ---
            window.abrirModalEditBeneficiario();

        }, { once: true });

        // Cerramos el Show
        modalShow.hide();
    });

});
</script>