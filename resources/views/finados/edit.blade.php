<div class="modal fade" id="editFinadoModal" tabindex="-1"
     aria-labelledby="editFinadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="editFinadoModalLabel">
                        <i class="bi bi-pencil-square me-2 text-muted"></i>Editar Finado
                    </h5>
                    <p class="text-muted small mb-0">Modifica la información del finado</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editFinadoForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" id="edit_id" name="id_finado">

                <div class="modal-body pt-3">

                    {{-- SECCIÓN 1: DATOS DEL FINADO --}}
                    <div class="section-block mb-3">
                        <span class="section-label">
                            <i class="bi bi-person me-1"></i> Datos del finado
                        </span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Nombre(s) <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edit_nombre" name="nombre"
                                       class="form-control"
                                       placeholder="Ej. Juan Carlos"
                                       required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Apellido paterno</label>
                                <input type="text" id="edit_apellido_paterno" name="apellido_paterno"
                                       class="form-control" placeholder="Ej. García">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Apellido materno</label>
                                <input type="text" id="edit_apellido_materno" name="apellido_materno"
                                       class="form-control" placeholder="Ej. López">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Fecha de fallecimiento</label>
                                <input type="date" id="edit_fecha_defuncion" name="fecha_defuncion"
                                       class="form-control">
                                <small class="text-muted">¿Cuándo falleció?</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Sexo</label>
                                <select id="edit_sexo" name="sexo" class="form-select">
                                    <option value="">Seleccionar</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tipo de construcción</label>
                                <select id="edit_tipo_construccion" name="tipo_construccion" class="form-select">
                                    <option value="">Sin construcción</option>
                                    <option value="cripta">Cripta</option>
                                    <option value="capilla">Capilla</option>
                                </select>
                                <small class="text-muted">Tipo de obra en la concesión, si aplica</small>
                            </div>

                        </div>
                    </div>

                    {{-- SECCIÓN 2: OBSERVACIONES --}}
                    <div class="section-block mb-3">
                        <span class="section-label">
                            <i class="bi bi-chat-left-text me-1"></i> Observaciones
                        </span>
                        <textarea id="edit_observaciones" name="observaciones"
                                  class="form-control" rows="2"
                                  placeholder="Notas adicionales sobre el finado (opcional)..."></textarea>
                    </div>

                    {{-- MOVIMIENTO --}}
                    <div class="section-block mb-3">
                        <span class="section-label">
                            <i class="bi bi-arrow-left-right me-1"></i> Registrar movimiento
                            <span class="text-muted fw-normal">(opcional)</span>
                        </span>

                        <div class="row g-3">

                            {{-- FILA 1 --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tipo de movimiento</label>
                                <select id="edit_tipo_movimiento" name="tipo_movimiento" class="form-select">
                                    <option value="">Sin cambio</option>
                                    <option value="inhumacion">Inhumación</option>
                                    <option value="exhumacion">Exhumación</option>
                                    <option value="reinhumacion">Reinhumación</option>
                                </select>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Solicitante</label>
                                <input type="text" id="edit_solicitante" name="solicitante"
                                       class="form-control">
                            </div>

                            {{-- FILA 2 --}}
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nueva ubicación</label>

                                <div class="input-group">
                                    <select id="edit_select_concesion" name="id_concesion">
                                        <option value=""></option>
                                        @foreach($concesiones as $c)
                                            @php
                                                $espacio = $c->lote?->espacioActual;
                                                $seccion = optional($espacio?->seccion)->nombre ?? '—';
                                                $tipo    = optional($espacio?->tipoEspacioFisico)->nombre ?? '';
                                                $nombre  = $espacio?->nombre ?? '';
                                                $numero  = $c->lote?->numero ?? '';
                                            @endphp
                                            <option value="{{ $c->id_concesion }}">
                                                {{ $seccion }}, {{ $tipo }} {{ $nombre }}, Lote {{ $numero }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="button" class="input-group-text" id="btnEditConcesion">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Fecha del movimiento</label>
                                <input type="date" id="edit_fecha_movimiento" name="fecha_movimiento"
                                       class="form-control">
                            </div>

                            {{-- FILA 3 --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">Observaciones del movimiento</label>
                                <textarea id="edit_obs_movimiento" name="obs_movimiento"
                                          class="form-control" rows="2"></textarea>
                            </div>

                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn bg-base text-white px-4">
                            Guardar cambios
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let formSubmittedFinado = false;
    let editModalOpenedFinado = false;
    let tsEditConcesion = null;

    // TomSelect
    const select = document.getElementById('edit_select_concesion');

    if (select) {
        tsEditConcesion = new TomSelect(select, {
            openOnFocus: false,
            create: false,
            placeholder: 'Buscar concesión...',
            allowEmptyOption: true,
            maxOptions: 5
        });

        document.getElementById('btnEditConcesion')?.addEventListener('click', () => {
            tsEditConcesion.open();
        });
    }

    // Submit seguro
    const form = document.getElementById('editFinadoForm');
    if (form) {
        form.addEventListener('submit', () => {
            formSubmittedFinado = true;
        });
    }

    // Evento cierre modal
    const modal = document.getElementById('editFinadoModal');
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            if (!formSubmittedFinado && editModalOpenedFinado) {
                window.location.href = "/finados";
            }
            editModalOpenedFinado = false;
        });
    }

    // Función global
    window.abrirModalEdit = function () {
        editModalOpenedFinado = true;
        new bootstrap.Modal(document.getElementById('editFinadoModal')).show();
    };

});
</script>