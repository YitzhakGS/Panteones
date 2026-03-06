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

            <form id="editTitularForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" id="edit_id" name="id_titular">

                <div class="modal-body pt-3">

                    {{-- ── SECCIÓN 1: Identificación ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Identificación</span>

                        <div class="row g-3 mt-1">

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-people me-1 text-muted"></i>
                                    Familia / Titular <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       id="edit_familia"
                                       name="familia"
                                       class="form-control"
                                       placeholder="Ej. García Pérez (o nombre del titular)"
                                       required>
                            </div>

                        </div>
                    </div>

                    {{-- ── SECCIÓN 2: Domicilio ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Domicilio</span>

                        <div class="row g-3 mt-1">

                            {{-- Calle --}}
                            <div class="col-md-7">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-house me-1 text-muted"></i>
                                    Calle y número <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       id="edit_domicilio"
                                       name="domicilio"
                                       class="form-control"
                                       placeholder="Calle, número, manzana, lote"
                                       required>
                            </div>

                            {{-- Colonia --}}
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-signpost me-1 text-muted"></i>
                                    Colonia <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       id="edit_colonia"
                                       name="colonia"
                                       class="form-control"
                                       required>
                            </div>

                            {{-- CP --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-mailbox me-1 text-muted"></i>
                                    C.P. <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       id="edit_cp"
                                       name="codigo_postal"
                                       class="form-control"
                                       maxlength="5"
                                       placeholder="00000"
                                       required>
                            </div>

                            {{-- Municipio --}}
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-building me-1 text-muted"></i>
                                    Municipio <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       id="edit_municipio"
                                       name="municipio"
                                       class="form-control"
                                       required>
                            </div>

                            {{-- Estado --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-map me-1 text-muted"></i>
                                    Estado <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       id="edit_estado"
                                       name="estado"
                                       class="form-control"
                                       placeholder="Ej. Hidalgo"
                                       required>
                            </div>

                        </div>
                    </div>

                    {{-- ── SECCIÓN 3: Contacto ── --}}
                    <div class="section-block mb-1">
                        <span class="section-label">Contacto</span>

                        <div class="row g-3 mt-1 align-items-end">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-telephone me-1 text-muted"></i>
                                    Teléfono <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       id="edit_telefono"
                                       name="telefono"
                                       class="form-control"
                                       placeholder="10 dígitos"
                                       required>
                            </div>

                            <div class="col-md-6 d-flex justify-content-end gap-2">
                                <button type="button"
                                        class="btn btn-light border"
                                        data-bs-dismiss="modal">
                                    <i class="bi bi-x me-1"></i>Cancelar
                                </button>

                                <button type="submit"
                                        class="btn bg-base text-white px-4">
                                    <i class="bi bi-check2-circle me-1"></i>Actualizar titular
                                </button>
                            </div>

                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>

<script>
/**
 * Control de flujo para el Modal de Edición de Titulares.
 * Este script garantiza que si el usuario cierra el modal sin guardar cambios,
 * sea redirigido automáticamente a la lista principal para limpiar el estado de la vista.
 */

/** * @type {boolean} formSubmitted - Bandera para rastrear si el usuario hizo clic en "Guardar".
 */
let formSubmitted = false;

/**
 * Escucha el evento de envío del formulario.
 * Si el formulario pasa las validaciones y se envía, desactivamos la redirección automática.
 */
document.getElementById('editTitularForm').addEventListener('submit', () => {
    formSubmitted = true;
});

/**
 * Evento de Bootstrap que se dispara cuando el modal termina de ocultarse.
 * Se encarga de la redirección si la edición fue cancelada.
 */
document.getElementById('editTitularModal')
    .addEventListener('hidden.bs.modal', function () {

        // Si el modal se cerró (clic fuera, botón X o Cancelar) 
        // pero NO se envió el formulario, forzamos el regreso al index.
        if (!formSubmitted) {
            window.location.href = "{{ route('titulares.index') }}";
        }
    });
</script>
