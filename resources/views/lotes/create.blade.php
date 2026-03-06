<div class="modal fade" id="createLoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-muted"></i>Nuevo Lote
                    </h5>
                    <p class="text-muted small mb-0">Completa los datos para registrar el lote</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('lotes.store') }}" method="POST">
                @csrf

                <div class="modal-body pt-3">

                    {{-- ── SECCIÓN 1: Identificación ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Identificación</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-hash me-1 text-muted"></i>Número de lote
                                </label>
                                <input type="text"
                                       name="numero"
                                       class="form-control @error('numero') is-invalid @enderror"
                                       value="{{ old('numero') }}"
                                       placeholder="Ej. 042">
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-rulers me-1 text-muted"></i>Superficie (m²)
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           step="0.01"
                                           name="metros_cuadrados"
                                           id="metros_cuadrados"
                                           class="form-control"
                                           placeholder="0.00"
                                           readonly>
                                    <span class="input-group-text text-muted">m²</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ── SECCIÓN 2: Medidas ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Medidas (metros)</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-arrow-up me-1"></i>Norte
                                </label>
                                <input type="number" step="0.01" name="med_norte"
                                       class="form-control" placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-arrow-down me-1"></i>Sur
                                </label>
                                <input type="number" step="0.01" name="med_sur"
                                       class="form-control" placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-arrow-right me-1"></i>Oriente
                                </label>
                                <input type="number" step="0.01" name="med_oriente"
                                       class="form-control" placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>Poniente
                                </label>
                                <input type="number" step="0.01" name="med_poniente"
                                       class="form-control" placeholder="0.00">
                            </div>

                        </div>
                    </div>

                    {{-- ── SECCIÓN 3: Colindancias ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Colindancias</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-arrow-up me-1"></i>Norte
                                </label>
                                <input type="text" name="col_norte"
                                       class="form-control" placeholder="Colindancia Norte">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-arrow-down me-1"></i>Sur
                                </label>
                                <input type="text" name="col_sur"
                                       class="form-control" placeholder="Colindancia Sur">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-arrow-right me-1"></i>Oriente
                                </label>
                                <input type="text" name="col_oriente"
                                       class="form-control" placeholder="Colindancia Oriente">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>Poniente
                                </label>
                                <input type="text" name="col_poniente"
                                       class="form-control" placeholder="Colindancia Poniente">
                            </div>

                        </div>
                    </div>

                    {{-- ── SECCIÓN 4: Ubicación ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Ubicación del lote</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-grid me-1 text-muted"></i>Sección / Cuadrilla
                                </label>
                                <select id="select-cuadrilla-ajax" class="form-select border-primary">
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
                                        id="select-espacio-ajax"
                                        class="form-select @error('id_espacio_fisico') is-invalid @enderror"
                                        required disabled>
                                    <option value="">Primero elija una cuadrilla...</option>
                                </select>
                                @error('id_espacio_fisico')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── SECCIÓN 5: Referencias ── --}}
                    <div class="section-block mb-1">
                        <span class="section-label">Referencias adicionales</span>
                        <div class="mt-1">
                            <textarea name="referencias" rows="2" class="form-control"
                                      placeholder="Notas, ubicación visual, referencias...">{{ old('referencias') }}</textarea>
                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn bg-base text-white px-4">
                        <i class="bi bi-check2-circle me-1"></i>Guardar lote
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
/**
 * Lógica para la Creación de Lotes
 * Maneja el cálculo automático de dimensiones y la carga dinámica de 
 * ubicaciones físicas mediante peticiones asíncronas.
 */

/* ==========================================================================
   CÁLCULO DE SUPERFICIE (MODO CREACIÓN)
   ========================================================================== */

/**
 * Escucha global de entradas de datos para calcular la superficie automáticamente.
 */
document.addEventListener('input', calcularSuperficie);

/**
 * Calcula el área total del lote basándose en el promedio de sus colindancias.
 * @returns {void} Actualiza el valor del input #metros_cuadrados.
 */
function calcularSuperficie() {
    // Extracción de valores numéricos de los inputs de medidas
    const norte    = parseFloat(document.querySelector('[name="med_norte"]').value) || 0;
    const sur      = parseFloat(document.querySelector('[name="med_sur"]').value) || 0;
    const oriente  = parseFloat(document.querySelector('[name="med_oriente"]').value) || 0;
    const poniente = parseFloat(document.querySelector('[name="med_poniente"]').value) || 0;

    /**
     * Validación lógica: 
     * Se requiere al menos una medida en el eje X y una en el eje Y para promediar.
     */
    if ((norte > 0 || sur > 0) && (oriente > 0 || poniente > 0)) {

        // Cálculo de promedios para terrenos con formas irregulares
        const anchoPromedio = ((norte + sur) / 2) || 0;
        const largoPromedio = ((oriente + poniente) / 2) || 0;

        const superficie = anchoPromedio * largoPromedio;

        // Formatea el resultado a 2 decimales si el valor es válido
        document.getElementById('metros_cuadrados').value =
            superficie > 0 ? superficie.toFixed(2) : '';
    }
}

/* ==========================================================================
   CARGA ASÍNCRONA DE ÁREAS (MODO CREACIÓN)
   ========================================================================== */

/**
 * Gestiona la dependencia entre el select de Cuadrilla y el de Espacio Físico.
 */
document.getElementById('select-cuadrilla-ajax').addEventListener('change', function() {
    const idCuadrilla = this.value;
    const selectEspacio = document.getElementById('select-espacio-ajax');

    // Estado inicial: Limpiar opciones y deshabilitar mientras carga
    selectEspacio.innerHTML = '<option value="">Cargando áreas...</option>';
    selectEspacio.disabled = true;

    // Validación de selección nula
    if (!idCuadrilla) {
        selectEspacio.innerHTML = '<option value="">Primero elija una cuadrilla...</option>';
        return;
    }

    /**
     * Fetch API: Solicita al servidor los espacios asociados a la cuadrilla.
     */
    fetch(`/api/cuadrillas/${idCuadrilla}/espacios`)
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            return response.json();
        })
        .then(data => {
            // Reiniciar select con opción por defecto
            selectEspacio.innerHTML = '<option value="">-- Seleccione el Área Específica --</option>';
            
            // Generación dinámica de nodos <option>
            data.forEach(espacio => {
                const option = document.createElement('option');
                // Valor interno (ID de la DB) y texto descriptivo concatenado
                option.value = espacio.id; 
                option.textContent = `${espacio.tipo} ${espacio.nombre}`;
                selectEspacio.appendChild(option);
            });

            // Habilitar para interacción del usuario
            selectEspacio.disabled = false;
        })
        .catch(error => {
            console.error('Error AJAX:', error);
            selectEspacio.innerHTML = '<option value="">Error al cargar áreas</option>';
        });
});

</script>