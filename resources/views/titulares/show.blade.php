<div class="modal fade" id="showTitularModal" tabindex="-1"
     aria-labelledby="showTitularModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="showTitularModalLabel">
                        <i class="bi bi-person-vcard me-2 text-muted"></i>Detalle del Titular
                    </h5>
                    <p class="text-muted small mb-0">Información registrada del titular</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <input type="hidden" id="show_id">

            <div class="modal-body pt-3">

            {{-- SECCIÓN 1: Identificación --}}
            <div class="section-block mb-3">
                <span class="section-label">Identificación y Contacto</span>
                <div class="row g-2 mt-1">
                    <div class="col-md-8">
                        <p class="text-muted small mb-0">Familia / Titular</p>
                        <p id="show_familia" class="fw-semibold mb-0"></p>
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

                {{-- SECCIÓN 4: Documentos --}}
                <div class="section-block mb-3">
                    <span class="section-label">Documentos</span>
                    <div class="row g-3 mt-1" id="show_documentos_container">
                        {{-- Se llena dinámicamente con JS --}}
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-end gap-2 mt-2">
                    <button type="button" class="btn btn-outline-danger"
                            onclick="confirmarEliminacion()">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                    <button type="button" class="btn bg-base text-white px-4"
                            id="btnEditarTitular">
                        <i class="bi bi-pencil-square me-1"></i>Editar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@include('titulares.edit')

<script>
function confirmarEliminacion() {
    const id = document.getElementById('show_id').value;
    Swal.fire({
        title: '¿Eliminar registro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = `/titulares/${id}`;
            form.submit();
        }
    });
}
// Variable global para guardar datos del titular actual
let titularActual = null;

// Donde llenas el modal show (la función que ya tienes), guarda el objeto:
// titularActual = titular;  ← agrégalo ahí

document.getElementById('btnEditarTitular').addEventListener('click', function () {
    const id = document.getElementById('show_id').value;

    // Cerrar modal show
    bootstrap.Modal.getInstance(document.getElementById('showTitularModal')).hide();

    // Rellenar campos básicos
    const form = document.getElementById('editTitularForm');
    form.action = `/titulares/${id}`;

    document.getElementById('edit_id').value         = id;
    document.getElementById('edit_familia').value    = document.getElementById('show_familia').textContent;
    document.getElementById('edit_domicilio').value  = document.getElementById('show_domicilio').textContent;
    document.getElementById('edit_colonia').value    = document.getElementById('show_colonia').textContent;
    document.getElementById('edit_cp').value         = document.getElementById('show_cp').textContent;
    document.getElementById('edit_municipio').value  = document.getElementById('show_municipio').textContent;
    document.getElementById('edit_estado').value     = document.getElementById('show_estado').textContent;
    document.getElementById('edit_telefono').value   = document.getElementById('show_telefono').textContent;

    // Limpiar links anteriores
    document.querySelectorAll('[id^="edit_doc_link_"]').forEach(el => el.innerHTML = '');

    // Usar datos ya cargados en titularActual (sin fetch extra)
    if (titularActual && titularActual.documentos) {
        titularActual.documentos.forEach(doc => {
            const span = document.getElementById(`edit_doc_link_${doc.id_tipo_documento}`);
            if (span) {
                span.innerHTML = `
                    <a href="/documentos/${doc.id_documento}" target="_blank"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-eye me-1"></i>Ver actual
                    </a>`;
            }
        });
    }

    // Usar la función expuesta por edit.blade.php
    window.abrirModalEdit();
});
</script>