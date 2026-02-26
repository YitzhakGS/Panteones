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

document.addEventListener('input', calcularSuperficie);

function calcularSuperficie() {
    const norte    = parseFloat(document.querySelector('[name="med_norte"]').value) || 0;
    const sur      = parseFloat(document.querySelector('[name="med_sur"]').value) || 0;
    const oriente  = parseFloat(document.querySelector('[name="med_oriente"]').value) || 0;
    const poniente = parseFloat(document.querySelector('[name="med_poniente"]').value) || 0;

    // Verificamos que al menos haya un par válido
    if ((norte > 0 || sur > 0) && (oriente > 0 || poniente > 0)) {

        const anchoPromedio = ((norte + sur) / 2) || 0;
        const largoPromedio = ((oriente + poniente) / 2) || 0;

        const superficie = anchoPromedio * largoPromedio;

        document.getElementById('metros_cuadrados').value =
            superficie > 0 ? superficie.toFixed(2) : '';
    }
}

document.getElementById('select-cuadrilla-ajax').addEventListener('change', function() {
    const idCuadrilla = this.value;
    const selectEspacio = document.getElementById('select-espacio-ajax');

    // Limpiamos el select de áreas y lo ponemos en estado "Cargando"
    selectEspacio.innerHTML = '<option value="">Cargando áreas...</option>';
    selectEspacio.disabled = true;

    if (!idCuadrilla) {
        selectEspacio.innerHTML = '<option value="">Primero elija una cuadrilla...</option>';
        return;
    }

    // Petición AJAX al servidor
    fetch(`/api/cuadrillas/${idCuadrilla}/espacios`)
        .then(response => {
            if (!response.ok) throw new Error('Error en la red');
            return response.json();
        })
        .then(data => {
            // Limpiamos y llenamos con la data real
            selectEspacio.innerHTML = '<option value="">-- Seleccione el Área Específica --</option>';
            
            data.forEach(espacio => {
                const option = document.createElement('option');
                // 1. Usamos .id porque así lo definimos en el map() del controlador
                option.value = espacio.id; 
                
                // 2. Concatenamos el nombre y el tipo: "Lote 1 - [Tipo]"
                option.textContent = `${espacio.tipo} ${espacio.nombre}`;
                
                selectEspacio.appendChild(option);
            });

            // Habilitamos el select solo si hay datos
            selectEspacio.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            selectEspacio.innerHTML = '<option value="">Error al cargar áreas</option>';
        });
});

</script>