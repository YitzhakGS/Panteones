<div class="modal fade" id="editLoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-muted"></i>Editar Lote
                    </h5>
                    <p class="text-muted small mb-0">Modifica la información del lote</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editLoteForm"
                  action="{{ route('lotes.update', $lote->id_lote) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <input type="hidden"
                       id="current_espacio_id"
                       value="{{ optional($lote->espacioActual)->id_espacio_fisico }}">

                <div class="modal-body pt-3">

                    {{-- ── IDENTIFICACIÓN ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Identificación</span>

                        <div class="row g-3 mt-1">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-hash me-1 text-muted"></i>Número de lote
                                </label>

                                <input type="text"
                                       name="numero"
                                       class="form-control"
                                       value="{{ old('numero', $lote->numero) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-rulers me-1 text-muted"></i>Superficie (m²)
                                </label>

                                <div class="input-group">
                                    <input type="number"
                                           step="0.01"
                                           name="metros_cuadrados"
                                           id="edit_metros_cuadrados"
                                           class="form-control"
                                           value="{{ old('metros_cuadrados', $lote->metros_cuadrados) }}"
                                           readonly>

                                    <span class="input-group-text text-muted">m²</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ── MEDIDAS ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Medidas (metros)</span>

                        <div class="row g-3 mt-1">

                            <div class="col-md-3">
                                <label class="form-label text-muted small">
                                    <i class="bi bi-arrow-up me-1"></i>Norte
                                </label>
                                <input type="number" step="0.01"
                                       name="med_norte"
                                       class="form-control"
                                       value="{{ old('med_norte', $lote->med_norte) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-muted small">
                                    <i class="bi bi-arrow-down me-1"></i>Sur
                                </label>
                                <input type="number" step="0.01"
                                       name="med_sur"
                                       class="form-control"
                                       value="{{ old('med_sur', $lote->med_sur) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-muted small">
                                    <i class="bi bi-arrow-right me-1"></i>Oriente
                                </label>
                                <input type="number" step="0.01"
                                       name="med_oriente"
                                       class="form-control"
                                       value="{{ old('med_oriente', $lote->med_oriente) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>Poniente
                                </label>
                                <input type="number" step="0.01"
                                       name="med_poniente"
                                       class="form-control"
                                       value="{{ old('med_poniente', $lote->med_poniente) }}">
                            </div>

                        </div>
                    </div>

                    {{-- ── COLINDANCIAS ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Colindancias</span>

                        <div class="row g-3 mt-1">

                            <div class="col-md-6">
                                <label class="form-label text-muted small">
                                    <i class="bi bi-arrow-up me-1"></i>Norte
                                </label>

                                <input type="text"
                                       name="col_norte"
                                       class="form-control"
                                       value="{{ old('col_norte', $lote->col_norte) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small">
                                    <i class="bi bi-arrow-down me-1"></i>Sur
                                </label>

                                <input type="text"
                                       name="col_sur"
                                       class="form-control"
                                       value="{{ old('col_sur', $lote->col_sur) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small">
                                    <i class="bi bi-arrow-right me-1"></i>Oriente
                                </label>

                                <input type="text"
                                       name="col_oriente"
                                       class="form-control"
                                       value="{{ old('col_oriente', $lote->col_oriente) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>Poniente
                                </label>

                                <input type="text"
                                       name="col_poniente"
                                       class="form-control"
                                       value="{{ old('col_poniente', $lote->col_poniente) }}">
                            </div>

                        </div>
                    </div>

                    {{-- ── UBICACIÓN ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Ubicación del lote</span>

                        <div class="row g-3 mt-1">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-grid me-1 text-muted"></i>Sección / Cuadrilla
                                </label>

                                <select id="edit-select-cuadrilla-ajax"
                                        class="form-select border-primary">
                                    <option value="">Seleccione Cuadrilla...</option>

                                    @foreach ($cuadrillas as $cuadrilla)
                                        <option value="{{ $cuadrilla->id_cuadrilla }}">
                                            {{ $cuadrilla->seccion->nombre }} - {{ $cuadrilla->nombre }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-map me-1 text-muted"></i>Área / Espacio Físico
                                </label>

                                <select name="id_espacio_fisico"
                                        id="edit-select-espacio-ajax"
                                        class="form-select"
                                        required>

                                    @if($lote->espacioActual)
                                        <option value="{{ $lote->espacioActual->id_espacio_fisico }}" selected>
                                            {{ $lote->espacioActual->tipoEspacioFisico->nombre }}
                                            {{ $lote->espacioActual->nombre }}
                                        </option>
                                    @else
                                        <option value="">Primero elija una cuadrilla...</option>
                                    @endif

                                </select>
                            </div>

                        </div>
                    </div>

                    {{-- ── REFERENCIAS ── --}}
                    <div class="section-block mb-1">
                        <span class="section-label">Referencias adicionales</span>

                        <textarea name="referencias"
                                  rows="2"
                                  class="form-control"
                                  placeholder="Notas, ubicación visual, referencias...">{{ old('referencias', $lote->referencias) }}</textarea>
                    </div>

                </div>

                {{-- BOTONES --}}
                <div class="modal-footer border-top-0">
                    <button type="button"
                            class="btn btn-light border"
                            data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancelar
                    </button>

                    <button type="submit"
                            class="btn bg-base text-white px-4">
                        <i class="bi bi-check2-circle me-1"></i>Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>



<script>
/**
 * Lógica Interactiva para el Formulario de Edición de Lotes.
 * Gestiona la carga dinámica de áreas mediante AJAX y el cálculo
 * automático de superficie basado en las medidas perimetrales.
 */

/* ==========================================================================
   SELECTS DEPENDIENTES (Cuadrilla -> Espacio)
   ========================================================================== */

/**
 * Escucha cambios en el select de Cuadrilla para actualizar el select de Espacios.
 * Implementa un estado de carga y manejo de errores.
 */
document.getElementById('edit-select-cuadrilla-ajax').addEventListener('change', function() {
    const idCuadrilla = this.value;
    const selectEspacio = document.getElementById('edit-select-espacio-ajax');

    // Estado visual de carga
    selectEspacio.innerHTML = '<option value="">Cargando áreas...</option>';
    selectEspacio.disabled = true;

    // Si se limpia la selección de cuadrilla
    if (!idCuadrilla) {
        selectEspacio.innerHTML = '<option value="">Primero elija una cuadrilla...</option>';
        return;
    }

    // Petición asíncrona a la API de espacios físicos
   fetch(`/api/cuadrillas/${idCuadrilla}/espacios`)
    .then(response => response.json())
    .then(data => {
        selectEspacio.innerHTML = '<option value="">-- Seleccione el Área Específica --</option>';

        const currentEspacioId =
            document.getElementById('current_espacio_id')?.value;

        data.forEach(espacio => {
            const option = document.createElement('option');
            option.value = espacio.id;
            option.textContent = `${espacio.tipo} ${espacio.nombre}`;

            // 🔥 AQUÍ ESTÁ LA MAGIA
            if (currentEspacioId && espacio.id == currentEspacioId) {
                option.selected = true;
            }

            selectEspacio.appendChild(option);
        });

        selectEspacio.disabled = false;
    })
    .catch(error => {
        console.error('Error al obtener espacios:', error);
        selectEspacio.innerHTML = '<option value="">Error al cargar áreas</option>';
    });
});

/* ==========================================================================
   CÁLCULO AUTOMÁTICO DE SUPERFICIE
   ========================================================================== */

const editModal = document.getElementById('editLoteModal');

/**
 * Escucha cualquier entrada de texto en el modal de edición.
 * Filtra los eventos 'input' para disparar el cálculo solo en campos de medidas.
 */
editModal.addEventListener('input', function (e) {
    const camposMedidas = ['med_norte', 'med_sur', 'med_oriente', 'med_poniente'];
    
    if (camposMedidas.includes(e.target.name)) {
        calcularSuperficieEdit();
    }
});

/**
 * Calcula la superficie (m²) del lote usando el promedio de sus lados.
 * Aplica la fórmula: ((Norte + Sur) / 2) * ((Oriente + Poniente) / 2)
 */
function calcularSuperficieEdit() {
    // Obtención de valores y conversión a flotante (0 si es inválido)
    const norte    = parseFloat(document.querySelector('#editLoteModal [name="med_norte"]').value) || 0;
    const sur      = parseFloat(document.querySelector('#editLoteModal [name="med_sur"]').value) || 0;
    const oriente  = parseFloat(document.querySelector('#editLoteModal [name="med_oriente"]').value) || 0;
    const poniente = parseFloat(document.querySelector('#editLoteModal [name="med_poniente"]').value) || 0;

    // Validación: Se requiere al menos un valor de eje vertical y uno horizontal
    if ((norte > 0 || sur > 0) && (oriente > 0 || poniente > 0)) {
        const anchoPromedio = (norte + sur) / 2;
        const largoPromedio = (oriente + poniente) / 2;
        const superficie = anchoPromedio * largoPromedio;

        // Actualización del campo de metros cuadrados con 2 decimales
        document.getElementById('edit_metros_cuadrados').value =
            superficie > 0 ? superficie.toFixed(2) : '';
    }
}


</script>
