<div class="modal fade" id="showTitularModal" tabindex="-1"
     aria-labelledby="showTitularModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            {{-- HEADER --}}
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

            {{-- BODY --}}
            <div class="modal-body pt-3">

                {{-- ── SECCIÓN 1: Identificación ── --}}
                <div class="section-block mb-3">
                    <span class="section-label">Identificación</span>
                    <div class="row g-3 mt-1">

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-people me-1 text-muted"></i>Familia / Titular
                            </label>
                            <input type="text" id="show_familia"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                    </div>
                </div>

                {{-- ── SECCIÓN 2: Domicilio ── --}}
                <div class="section-block mb-3">
                    <span class="section-label">Domicilio</span>
                    <div class="row g-3 mt-1">

                        <div class="col-md-7">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-house me-1 text-muted"></i>Calle y número
                            </label>
                            <input type="text" id="show_domicilio"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-signpost me-1 text-muted"></i>Colonia
                            </label>
                            <input type="text" id="show_colonia"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-mailbox me-1 text-muted"></i>C.P.
                            </label>
                            <input type="text" id="show_cp"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-building me-1 text-muted"></i>Municipio
                            </label>
                            <input type="text" id="show_municipio"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-map me-1 text-muted"></i>Estado
                            </label>
                            <input type="text" id="show_estado"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                    </div>
                </div>

                {{-- ── SECCIÓN 3: Contacto + Botones ── --}}
                <div class="section-block mb-1">
                    <span class="section-label">Contacto</span>
                    <div class="row g-3 mt-1 align-items-end">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-telephone me-1 text-muted"></i>Teléfono
                            </label>
                            <input type="text" id="show_telefono"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                        <div class="col-md-6 d-flex justify-content-end gap-2">
                            <button type="button"
                                    class="btn btn-outline-danger"
                                    onclick="confirmarEliminacion()">
                                <i class="bi bi-trash me-1"></i>Eliminar
                            </button>
                            <button type="button"
                                    class="btn bg-base text-white px-4"
                                    id="btnEditarTitular">
                                <i class="bi bi-pencil-square me-1"></i>Editar
                            </button>
                        </div>

                    </div>
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
/**
 * Lógica de interacción para la vista de detalle de Titulares.
 * Incluye la confirmación de borrado y el traspaso de datos entre modales.
 */

/**
 * Muestra una alerta de confirmación estética antes de eliminar un registro.
 * Utiliza la librería SweetAlert2.
 * * @param {number|string} id - El identificador único del registro a eliminar.
 */
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

            // Cambia la ruta dinámicamente
            form.action = `/titulares/${id}`;

            // Enviar formulario
            form.submit();

        }

    });

}

/**
 * Manejador para el botón "Editar" dentro del modal de visualización.
 * Transfiere los datos del modal 'Show' al modal 'Edit' para evitar peticiones AJAX extra.
 */
document.getElementById('btnEditarTitular').addEventListener('click', function () {

    // 1. Obtener el ID del registro desde el campo oculto del modal actual
    const id = document.getElementById('show_id').value;

    // 2. Cerrar el modal de visualización (Show) usando la instancia de Bootstrap
    const modalShowElement = document.getElementById('showTitularModal');
    bootstrap.Modal.getInstance(modalShowElement).hide();

    // 3. Configurar el formulario de edición
    const form = document.getElementById('editTitularForm');
    form.action = `/titulares/${id}`; // Ruta RESTful para el Update

    // 4. Traspaso de valores entre elementos del DOM
    // Se toma el valor de los campos de 'show' y se asigna a los de 'edit'
    document.getElementById('edit_id').value        = id;
    document.getElementById('edit_familia').value   = document.getElementById('show_familia').value;
    document.getElementById('edit_domicilio').value = document.getElementById('show_domicilio').value;
    document.getElementById('edit_colonia').value   = document.getElementById('show_colonia').value;
    document.getElementById('edit_cp').value        = document.getElementById('show_cp').value;
    document.getElementById('edit_municipio').value = document.getElementById('show_municipio').value;
    document.getElementById('edit_estado').value    = document.getElementById('show_estado').value;
    document.getElementById('edit_telefono').value  = document.getElementById('show_telefono').value;

    // 5. Inicializar y mostrar el modal de edición
    const modalEditElement = document.getElementById('editTitularModal');
    const modalEdit = new bootstrap.Modal(modalEditElement);
    modalEdit.show();
});
</script>


