<div class="modal fade" id="createLoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Lote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('lotes.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    {{-- =============================
                    1️⃣ IDENTIFICACIÓN
                    ============================== --}}
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Número de lote</label>
                                <input type="text"
                                       name="numero"
                                       class="form-control @error('numero') is-invalid @enderror"
                                       value="{{ old('numero') }}">
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Superficie (m²)</label>
                                <input type="number"
                                       step="0.01"
                                       name="metros_cuadrados"
                                       id="metros_cuadrados"
                                       class="form-control"
                                       readonly>
                            </div>
                        </div>
                    </div>

                    {{-- =============================
                    2️⃣ MEDIDAS
                    ============================== --}}
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Medidas (metros)</h6>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="med_norte" class="form-control" placeholder="Norte">
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="med_sur" class="form-control" placeholder="Sur">
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="med_oriente" class="form-control" placeholder="Oriente">
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="med_poniente" class="form-control" placeholder="Poniente">
                            </div>
                        </div>
                    </div>

                    {{-- =============================
                    3️⃣ COLINDANCIAS
                    ============================== --}}
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Colindancias</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <input type="text" name="col_norte" class="form-control" placeholder="Colindancia Norte">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" name="col_sur" class="form-control" placeholder="Colindancia Sur">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="col_oriente" class="form-control" placeholder="Colindancia Oriente">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="col_poniente" class="form-control" placeholder="Colindancia Poniente">
                            </div>
                        </div>
                    </div>


                    {{-- =============================
                    5️⃣ ESPACIO FÍSICO (FUSIONADO)
                    ============================== --}}
                    <div class="mb-4 ">
                        <h6 class="text-muted mb-3"> Ubicación del Lote</h6>
                        <div class="row">
                            {{-- Select 1: Cuadrilla (Carga inicial) --}}
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">Sección / Cuadrilla</label>
                                <select id="select-cuadrilla-ajax" class="form-select border-primary">
                                    <option value="">Seleccione Cuadrilla...</option>
                                    @foreach ($cuadrillas as $cuadrilla)
                                        <option value="{{ $cuadrilla->id_cuadrilla }}">
                                            {{ $cuadrilla->seccion->nombre }} - {{ $cuadrilla->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Select 2: Espacio Físico (Carga dinámica) --}}
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">Área / Espacio Físico</label>
                                <select name="id_espacio_fisico" id="select-espacio-ajax" 
                                        class="form-select @error('id_espacio_fisico') is-invalid @enderror" 
                                        required disabled>
                                    <option value="">Primero elija una cuadrilla...</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    {{-- =============================
                    4️⃣ REFERENCIAS
                    ============================== --}}
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Referencias adicionales</h6>
                        <textarea name="referencias" rows="1" class="form-control"
                                  placeholder="Notas, ubicación visual, referencias...">{{ old('referencias') }}</textarea>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn bg-base text-white">
                        Guardar
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