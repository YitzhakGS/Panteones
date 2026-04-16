<div class="modal fade" id="movimientoFinadoModal" tabindex="-1"
     aria-labelledby="movimientoFinadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="movimientoFinadoModalLabel">
                        <i class="bi bi-arrow-left-right me-2 text-muted"></i>
                        <span id="mov_titulo">Registrar movimiento</span>
                    </h5>
                    <p class="text-muted small mb-0" id="mov_subtitulo"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pt-3" style="max-height: 80vh; overflow-y: auto;">

                {{-- CAMPOS OCULTOS --}}
                <input type="hidden" id="mov_tipo">
                <input type="hidden" id="mov_id_finado">
                <input type="hidden" id="mov_concesion_actual">
                <input type="hidden" id="mov_lote_id">

                <div class="row g-3">

                    {{-- FECHA --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Fecha <span class="text-danger">*</span>
                        </label>
                        <input type="date" id="mov_fecha" class="form-control">
                    </div>

                    {{-- SOLICITANTE --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            Solicitante <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="mov_solicitante" class="form-control"
                               placeholder="Nombre del solicitante">
                    </div>

                    {{-- TIPO DE DESTINO --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Tipo de movimiento</label>

                        <!-- PRINCIPALES -->
                        <div class="d-flex gap-3 mb-2">

                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="mov_tipo_destino" id="radio_interno" value="interno">
                                <label class="form-check-label" for="radio_interno">
                                    <i class="bi bi-arrow-left-right me-1"></i>
                                    Mover a otra ubicación
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="mov_tipo_destino" id="radio_misma" value="misma">
                                <label class="form-check-label" for="radio_misma">
                                    <i class="bi bi-geo me-1"></i>
                                    Misma ubicación
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="mov_tipo_destino" id="radio_externo" value="externo">
                                <label class="form-check-label" for="radio_externo">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>
                                    Externo / Osario
                                </label>
                            </div>

                        </div>

                        <!-- 🔥 SUBTIPO SOLO PARA INTERNO -->
                        <div id="subtipo_lote" class="ms-3 mt-2" style="display:none;">

                            <label class="form-label small text-muted mb-1">
                                ¿Cómo deseas mover el lote?
                            </label>

                            <div class="d-flex gap-3">

                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                        name="mov_modo_lote" id="radio_lote_editar" value="editar" checked>
                                    <label class="form-check-label" for="radio_lote_editar">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Editar lote actual
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                        name="mov_modo_lote" id="radio_lote_existente" value="existente" >
                                    <label class="form-check-label" for="radio_lote_existente">
                                        <i class="bi bi-box-seam me-1"></i>
                                        Usar lote existente
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- INTERNO: CONCESIÓN --}}
                    <div class="col-12" id="wrapper_interno" style="display:none;">

                        <label class="form-label fw-semibold">
                            Nueva ubicación <span class="text-danger">*</span>
                        </label>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Ubicación actual</label>

                            <div class="form-control bg-light">
                                <span id="mov_ubicacion_texto">Sin ubicación</span>
                            </div>

                            <small class="text-muted">
                                Esta es la ubicación actual del finado
                            </small>
                        </div>

                        {{-- 👇 NUEVO --}}
                        <div id="subform_lote" style="display:none;">
                            @include('lotes._form', ['secciones' => $secciones])
                        </div>

                        <div id="wrapper_lote_existente" style="display:none;">
                            <label class="form-label">Seleccionar lote existente</label>

                            <select id="select_lote_existente" class="form-control">
                                <option value="">Buscar lote...</option>
                                @foreach($lotes as $l)
                                    <option value="{{ $l->id_lote }}">
                                        {{ $l->numero }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- EXTERNO --}}
                    <div class="col-12" id="wrapper_externo" style="display:none;">
                        <label class="form-label fw-semibold">Destino externo</label>
                        <input type="text" id="mov_destino_externo" class="form-control"
                               placeholder="Ej. Osario municipal, otro panteón...">
                    </div>

                    {{-- OBSERVACIONES --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Observaciones</label>
                        <textarea id="mov_observaciones" class="form-control" rows="2"
                                  placeholder="Notas adicionales (opcional)..."></textarea>
                    </div>

                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn bg-base text-white px-4"
                            id="mov_btn_guardar">
                        Guardar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let tomLotes;
    const modal = document.getElementById('movimientoFinadoModal');
    const btnGuardar = document.getElementById('mov_btn_guardar');

    let formSubmitted = false;

    // -------------------------
    // CONTROL VISUAL (RADIOS)
    // -------------------------
    function actualizarUI() {
        const tipo = document.querySelector('input[name="mov_tipo_destino"]:checked')?.value;

        const interno = document.getElementById('wrapper_interno');
        const externo = document.getElementById('wrapper_externo');
        const subtipo = document.getElementById('subtipo_lote');

        if (interno) interno.style.display = tipo === 'interno' ? '' : 'none';
        if (externo) externo.style.display = tipo === 'externo' ? '' : 'none';

        // SOLO controlas los radios secundarios aquí
        if (subtipo) subtipo.style.display = tipo === 'interno' ? '' : 'none';
    }

    document.querySelectorAll('input[name="mov_tipo_destino"]').forEach(radio => {
        radio.addEventListener('change', actualizarUI);
    });

    // -------------------------
    // EVENTOS DEL MODAL
    // -------------------------
    if (modal) {

        modal.addEventListener('shown.bs.modal', function () {
            actualizarUI();
            if (!tomLotes) {
                tomLotes = new TomSelect('#select_lote_existente', {
                    valueField: 'value',
                    labelField: 'text',
                    searchField: 'text',
                    placeholder: 'Buscar lote...',
                    create: false,
                });
            }
            document.querySelector('input[name="mov_modo_lote"]:checked')?.dispatchEvent(new Event('change'));
        });

        modal.addEventListener('hidden.bs.modal', function () {
            if (!formSubmitted) {
                window.location.href = "/finados";
            }
            formSubmitted = false;
        });
    }

    // -------------------------
    // SUBFORM (LOTES)
    // -------------------------
    // 👇 SOLO UNA VEZ (NO dentro del modal)
    document.querySelectorAll('.lote-measure-input').forEach(input => {
        input.addEventListener('input', function () {
            calcularSuperficie('lote');
        });
    });

    document.getElementById('lote_seccion')?.addEventListener('change', function () {
        cargarEspaciosFisicos({
            seccionId: this.value,
            selectId: 'lote_espacio'
        });
    });

    // -------------------------
    // GUARDAR MOVIMIENTO
    // -------------------------
    btnGuardar?.addEventListener('click', async function () {

        formSubmitted = true;

        const idFinado            = document.getElementById('mov_id_finado').value;
        const fecha               = document.getElementById('mov_fecha').value;
        const solicitante         = document.getElementById('mov_solicitante').value;
        const observaciones       = document.getElementById('mov_observaciones').value;
        const tipoDestino         = document.querySelector('input[name="mov_tipo_destino"]:checked')?.value;
        const id_ubicacion_actual = document.getElementById('mov_concesion_actual')?.value;


        // ---------------- VALIDACIONES
        if (!fecha) {
            Swal.fire('Error', 'La fecha es requerida', 'error');
            formSubmitted = false;
            return;
        }

        if (!solicitante) {
            Swal.fire('Error', 'El solicitante es requerido', 'error');
            formSubmitted = false;
            return;
        }

        if (!tipoDestino) {
            Swal.fire('Error', 'Selecciona un tipo de movimiento', 'error');
            formSubmitted = false;
            return;
        }

        let endpoint = '';
        let payload = {
            fecha,
            solicitante,
            observaciones,
            id_ubicacion_actual
        };

        console.log(payload);

        // ---------------- LOGICA
        if (tipoDestino === 'externo') {

            endpoint = 'exhumar';
            payload.es_externo = true;
            payload.ubicacion_externa =
                document.getElementById('mov_destino_externo').value;

        } else if (tipoDestino === 'misma') {

            endpoint = 'exhumar';
            payload.es_externo = false;
            payload.ubicacion_externa = null;

        } else if (tipoDestino === 'interno') {

            const modo = document.querySelector('input[name="mov_modo_lote"]:checked')?.value;

            let id_lote = null;

            // 🔥 1. DECIDIR DE DÓNDE SALE EL LOTE
            if (modo === 'existente') {

                if (!tomLotes) {
                    Swal.fire('Error', 'El selector de lotes no está listo', 'error');
                    formSubmitted = false;
                    return;
                }

                id_lote = tomLotes.getValue();// 👈 TomSelect

                if (!id_lote) {
                    Swal.fire('Error', 'Selecciona un lote existente', 'error');
                    formSubmitted = false;
                    return;
                }

                payload.modo = 'existente';

            } else {

                id_lote = document.getElementById('lote_id')?.value
                    || document.getElementById('mov_lote_id')?.value;

                if (!id_lote) {
                    Swal.fire('Error', 'No hay lote seleccionado', 'error');
                    formSubmitted = false;
                    return;
                }

                payload.modo = 'editar';

                // 🔥 SOLO SI EDITAS, mandas datos del lote
                payload.numero = document.getElementById('lote_numero')?.value;
                payload.metros_cuadrados = document.getElementById('lote_metros_cuadrados')?.value;

                payload.med_norte = document.getElementById('lote_med_norte')?.value;
                payload.med_sur = document.getElementById('lote_med_sur')?.value;
                payload.med_oriente = document.getElementById('lote_med_oriente')?.value;
                payload.med_poniente = document.getElementById('lote_med_poniente')?.value;

                payload.col_norte = document.getElementById('lote_col_norte')?.value;
                payload.col_sur = document.getElementById('lote_col_sur')?.value;
                payload.col_oriente = document.getElementById('lote_col_oriente')?.value;
                payload.col_poniente = document.getElementById('lote_col_poniente')?.value;

                payload.referencias = document.getElementById('lote_referencias')?.value;

                payload.id_espacio_fisico = document.getElementById('lote_espacio')?.value;
            }

            // 🔥 SIEMPRE mandas el lote (sea modo que sea)
            payload.id_lote = id_lote;

            // ---------------- endpoint (igual que ya tenías)
            const estadoActual =
                document.getElementById('show_estado')?.textContent?.trim().toUpperCase()
                || window.finadoActual?.estado?.toUpperCase();

            endpoint = estadoActual === 'EXHUMADO'
                ? 'reinhumar'
                : 'mover';
        }

        // ---------------- REQUEST
        try {
            const res = await fetch(`/finados/${idFinado}/${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(payload),
            });

            const data = await res.json();

            if (!res.ok) {
                Swal.fire('Error', data.error ?? 'Ocurrió un error', 'error');
                formSubmitted = false;
                return;
            }

            Swal.fire({
                icon: 'success',
                title: 'Movimiento registrado correctamente',
                timer: 1500,
                showConfirmButton: false
            }).then(() => window.location.reload());

        } catch (e) {
            Swal.fire('Error', 'Error de conexión', 'error');
            formSubmitted = false;
        }
    });

    document.querySelectorAll('input[name="mov_modo_lote"]').forEach(radio => {
        radio.addEventListener('change', function () {

            const subform = document.getElementById('subform_lote');
            const selector = document.getElementById('wrapper_lote_existente');

            if (this.value === 'editar') {
                subform.style.display = '';
                selector.style.display = 'none';
            } else {
                subform.style.display = 'none';
                selector.style.display = '';
            }
        });
    });

    

});
</script>