<div class="modal fade" id="editConcesionModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-muted"></i>Editar Concesión
                    </h5>
                    <p class="text-muted small mb-0">Modifica los datos de la concesión</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editConcesionForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" id="edit_id_concesion">

                <div class="modal-body pt-3">

                    {{-- DATOS GENERALES --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Datos generales</span>

                        <div class="row g-3 mt-1">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>Lote
                                </label>

                                <select id="edit_lote" name="id_lote" class="form-select" required>
                                    @foreach ($lotes as $lote)
                                        <option value="{{ $lote->id_lote }}">
                                            Lote {{ $lote->numero }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person me-1 text-muted"></i>Titular
                                </label>

                                <select id="edit_titular" name="id_titular" class="form-select" required>
                                    @foreach ($titulares as $titular)
                                        <option value="{{ $titular->id_titular }}">
                                            {{ $titular->familia }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    {{-- CONCESIÓN --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Concesión</span>

                        <div class="row g-3 mt-1">

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-bookmarks me-1 text-muted"></i>Uso funerario
                                </label>

                                <select id="edit_uso" name="id_uso_funerario" class="form-select" required>
                                    @foreach ($usos as $uso)
                                        <option value="{{ $uso->id_uso_funerario }}">
                                            {{ $uso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event me-1 text-muted"></i>Fecha inicio
                                </label>

                                <input type="date"
                                       id="edit_fecha"
                                       name="fecha_inicio"
                                       class="form-control"
                                       required>
                            </div>

                        </div>
                    </div>

                    {{-- REFRÉNDO --}}
                    <div class="section-block mb-1">
                        <span class="section-label">Refrendo inicial y observaciones</span>

                        <div class="row g-3 mt-1">

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-cash-coin me-1 text-muted"></i>Monto
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">$</span>

                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           id="edit_monto"
                                           name="monto"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="col-md-9">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-chat-left-text me-1 text-muted"></i>Observaciones
                                </label>

                                <textarea id="edit_observaciones"
                                          name="observaciones"
                                          rows="2"
                                          class="form-control"></textarea>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn bg-base text-white px-4">
                        <i class="bi bi-check2-circle me-1"></i>Actualizar concesión
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
/**
 * Control de flujo para el Modal de Edición de Concesiones.
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
document.getElementById('editConcesionForm').addEventListener('submit', () => {
    formSubmitted = true;
});

/**
 * Evento de Bootstrap que se dispara cuando el modal termina de ocultarse.
 * Se encarga de la redirección si la edición fue cancelada.
 */
document.getElementById('editConcesionModal')
    .addEventListener('hidden.bs.modal', function () {

        // Si el modal se cerró (clic fuera, botón X o Cancelar) 
        // pero NO se envió el formulario, forzamos el regreso al index.
        if (!formSubmitted) {
            window.location.href = "{{ route('concesiones.index') }}";
        }
    });
</script>