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

                    {{-- INTERNO: CONCESIÓN --}}
                    <div class="col-12" id="wrapper_interno" style="display:none;">

                        <label class="form-label fw-semibold">
                            Nueva ubicación <span class="text-danger">*</span>
                        </label>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Ubicación actual</label>
                            <div class="form-control bg-light">
                                <span id="mov_ubicacion_texto">Sin ubicación</span>
                            </div>
                            <small class="text-muted">Esta es la ubicación actual del finado</small>
                        </div>

                        {{-- Seleccionar lote existente --}}
                        <div id="wrapper_lote_existente">
                            <label class="form-label fw-semibold">
                                Seleccionar lote destino <span class="text-danger">*</span>
                            </label>
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
(function () {

    // 🔒 evitar múltiples inicializaciones
    if (window.movimientoFinadoInit) return;
    window.movimientoFinadoInit = true;

    let tomLotes = null;
    let procesando = false;

    const modal = document.getElementById('movimientoFinadoModal');
    if (!modal) return;

    // 🔥 eliminar listeners duplicados
    const originalBtn = document.getElementById('mov_btn_guardar');
    if (!originalBtn) return;

    const btnGuardar = originalBtn.cloneNode(true);
    originalBtn.parentNode.replaceChild(btnGuardar, originalBtn);

    let formSubmitted = false;

    // -------------------------
    // UI DINÁMICA
    // -------------------------
    function actualizarUI() {

        const tipo    = document.querySelector('input[name="mov_tipo_destino"]:checked')?.value;
        const interno = document.getElementById('wrapper_interno');
        const externo = document.getElementById('wrapper_externo');

        if (interno) interno.style.display = (tipo === 'interno') ? '' : 'none';
        if (externo) externo.style.display = (tipo === 'externo') ? '' : 'none';

        // init TomSelect SOLO una vez
        if (tipo === 'interno' && !tomLotes) {
            setTimeout(() => {
                tomLotes = new TomSelect('#select_lote_existente', {
                    valueField: 'value',
                    labelField: 'text',
                    searchField: 'text',
                    placeholder: 'Buscar lote...',
                    create: false,
                });
            }, 100);
        }
    }

    document.addEventListener('change', function (e) {
        if (e.target.name === 'mov_tipo_destino') {
            actualizarUI();
        }
    });

    modal.addEventListener('shown.bs.modal', actualizarUI);

    modal.addEventListener('hidden.bs.modal', function () {
        if (!formSubmitted) {
            console.log('↩️ Modal cerrado sin guardar');
        }
        formSubmitted = false;
        procesando = false;
        btnGuardar.disabled = false;
    });

    // -------------------------
    // GUARDAR
    // -------------------------
    btnGuardar.onclick = async function () {

        if (procesando) return;

        procesando = true;
        btnGuardar.disabled = true;
        formSubmitted = true;

        const idFinado        = document.getElementById('mov_id_finado').value;
        const fecha           = document.getElementById('mov_fecha').value;
        const solicitante     = document.getElementById('mov_solicitante').value;
        const observaciones   = document.getElementById('mov_observaciones').value;
        const tipoDestino     = document.querySelector('input[name="mov_tipo_destino"]:checked')?.value;
        const concesionActual = document.getElementById('mov_concesion_actual')?.value;

        // VALIDACIÓN
        if (!fecha || !solicitante || !tipoDestino) {
            console.error('❌ Validación fallida', {
                fecha,
                solicitante,
                tipoDestino
            });

            procesando = false;
            btnGuardar.disabled = false;
            formSubmitted = false;
            return;
        }

        let endpoint = '';
        let payload  = {
            fecha,
            solicitante,
            observaciones,
            id_ubicacion_actual: concesionActual
        };

        // -------------------------
        // LÓGICA
        // -------------------------
        if (tipoDestino === 'externo') {

            endpoint = 'exhumar';
            payload.es_externo        = true;
            payload.ubicacion_externa = document.getElementById('mov_destino_externo').value;

        } else if (tipoDestino === 'misma') {

            endpoint = 'mover';
            payload.id_lote = null;
            payload.es_misma_ubicacion = true;

        } else if (tipoDestino === 'interno') {

            if (!tomLotes) {
                console.error('❌ TomSelect no inicializado');
                reset();
                return;
            }

            const loteDestino = tomLotes.getValue();

            if (!loteDestino) {
                console.error('❌ No seleccionaste lote');
                reset();
                return;
            }

            payload.id_lote = loteDestino;
            endpoint = 'mover';
        }

        // DEBUG
        console.log('📤 REQUEST:', {
            url: `/finados/${idFinado}/${endpoint}`,
            payload
        });

        if (payload.id_lote) {
            console.log('🆕 LOTE DESTINO:', payload.id_lote);
        } else {
            console.log('📍 MISMA UBICACIÓN / EXTERNO');
        }

        // -------------------------
        // FETCH
        // -------------------------
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

            console.log('📥 RESPONSE:', data);

            if (!res.ok) {
                throw new Error(data.error || 'Error desconocido');
            }

            console.log('✅ Movimiento guardado correctamente');

            Swal.fire({
                icon: 'success',
                title: 'Movimiento registrado correctamente',
                timer: 1500,
                showConfirmButton: false
            }).then(() => window.location.reload());

        } catch (e) {
            console.error('❌ ERROR:', e.message);
            reset();
        }

        function reset() {
            procesando = false;
            btnGuardar.disabled = false;
            formSubmitted = false;
        }
    };

})();
</script>