<div class="modal fade" id="editLoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header">
                <h5 class="modal-title">Editar Lote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editLoteForm"
                  action="{{ route('lotes.update', $lote->id_lote) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    {{-- =============================
                    IDENTIFICACIÓN
                    ============================== --}}
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Número de lote</label>
                                <input type="text"
                                       name="numero"
                                       class="form-control @error('numero') is-invalid @enderror"
                                       value="{{ old('numero', $lote->numero) }}">
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Superficie (m²)</label>
                                <input type="number"
                                       step="0.01"
                                       name="metros_cuadrados"
                                       id="edit_metros_cuadrados"
                                       class="form-control"
                                       value="{{ old('metros_cuadrados', $lote->metros_cuadrados) }}"
                                       readonly>
                            </div>
                        </div>
                    </div>

                    {{-- =============================
                    MEDIDAS
                    ============================== --}}
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Medidas (metros)</h6>

                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="number" step="0.01"
                                       name="med_norte"
                                       class="form-control"
                                       placeholder="Norte"
                                       value="{{ old('med_norte', $lote->med_norte) }}">
                            </div>

                            <div class="col-md-3">
                                <input type="number" step="0.01"
                                       name="med_sur"
                                       class="form-control"
                                       placeholder="Sur"
                                       value="{{ old('med_sur', $lote->med_sur) }}">
                            </div>

                            <div class="col-md-3">
                                <input type="number" step="0.01"
                                       name="med_oriente"
                                       class="form-control"
                                       placeholder="Oriente"
                                       value="{{ old('med_oriente', $lote->med_oriente) }}">
                            </div>

                            <div class="col-md-3">
                                <input type="number" step="0.01"
                                       name="med_poniente"
                                       class="form-control"
                                       placeholder="Poniente"
                                       value="{{ old('med_poniente', $lote->med_poniente) }}">
                            </div>
                        </div>
                    </div>

                    {{-- =============================
                    COLINDANCIAS
                    ============================== --}}
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Colindancias</h6>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <input type="text"
                                       name="col_norte"
                                       class="form-control"
                                       placeholder="Colindancia Norte"
                                       value="{{ old('col_norte', $lote->col_norte) }}">
                            </div>

                            <div class="col-md-6 mb-2">
                                <input type="text"
                                       name="col_sur"
                                       class="form-control"
                                       placeholder="Colindancia Sur"
                                       value="{{ old('col_sur', $lote->col_sur) }}">
                            </div>

                            <div class="col-md-6">
                                <input type="text"
                                       name="col_oriente"
                                       class="form-control"
                                       placeholder="Colindancia Oriente"
                                       value="{{ old('col_oriente', $lote->col_oriente) }}">
                            </div>

                            <div class="col-md-6">
                                <input type="text"
                                       name="col_poniente"
                                       class="form-control"
                                       placeholder="Colindancia Poniente"
                                       value="{{ old('col_poniente', $lote->col_poniente) }}">
                            </div>
                        </div>
                    </div>

                    {{-- =============================
                     UBICACIÓN DINÁMICA (EDIT)
                    ============================== --}}
                    <div class="mb-4 p-3 bg-light rounded border">
                        <h6 class="text-muted mb-3"><i class="fas fa-map-marker-alt"></i> Ubicación del Lote</h6>
                        <div class="row">
                            {{-- Select 1: Cuadrilla --}}
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">Sección / Cuadrilla</label>
                                <select id="edit-select-cuadrilla-ajax" class="form-select border-primary">
                                    <option value="">Seleccione Cuadrilla...</option>
                                    @foreach ($cuadrillas as $cuadrilla)
                                        <option value="{{ $cuadrilla->id_cuadrilla }}">
                                            {{ $cuadrilla->seccion->nombre }} - {{ $cuadrilla->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Select 2: Espacio Físico --}}
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">Área / Espacio Físico</label>
                                <select name="id_espacio_fisico" id="edit-select-espacio-ajax" 
                                        class="form-select @error('id_espacio_fisico') is-invalid @enderror" 
                                        required>
                                    @if($lote->espacioActual)
                                        <option value="{{ $lote->espacioActual->id_espacio_fisico }}" selected>
                                            {{ $lote->espacioActual->tipoEspacioFisico->nombre }} {{ $lote->espacioActual->nombre }}
                                        </option>
                                    @else
                                        <option value="">Primero elija una cuadrilla...</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- =============================
                    REFERENCIAS
                    ============================== --}}
                    <div>
                        <h6 class="text-muted mb-3">Referencias adicionales</h6>

                        <textarea name="referencias"
                                  rows="1"
                                  class="form-control"
                                  placeholder="Notas, ubicación visual, referencias...">{{ old('referencias', $lote->referencias) }}</textarea>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn bg-base text-white">
                        Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>



<script>
// Lógica para el Modal de EDIT
document.getElementById('edit-select-cuadrilla-ajax').addEventListener('change', function() {
    const idCuadrilla = this.value;
    const selectEspacio = document.getElementById('edit-select-espacio-ajax');

    selectEspacio.innerHTML = '<option value="">Cargando áreas...</option>';
    selectEspacio.disabled = true;

    if (!idCuadrilla) {
        selectEspacio.innerHTML = '<option value="">Primero elija una cuadrilla...</option>';
        return;
    }

    fetch(`/api/cuadrillas/${idCuadrilla}/espacios`)
        .then(response => response.json())
        .then(data => {
            selectEspacio.innerHTML = '<option value="">-- Seleccione el Área Específica --</option>';
            data.forEach(espacio => {
                const option = document.createElement('option');
                option.value = espacio.id;
                option.textContent = `${espacio.tipo} ${espacio.nombre}`;
                selectEspacio.appendChild(option);
            });
            selectEspacio.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            selectEspacio.innerHTML = '<option value="">Error al cargar áreas</option>';
        });
});

const editModal = document.getElementById('editLoteModal');

editModal.addEventListener('input', function (e) {
    if (
        e.target.name === 'med_norte' ||
        e.target.name === 'med_sur' ||
        e.target.name === 'med_oriente' ||
        e.target.name === 'med_poniente'
    ) {
        calcularSuperficieEdit();
    }
});

function calcularSuperficieEdit() {
    const norte    = parseFloat(document.querySelector('#editLoteModal [name="med_norte"]').value) || 0;
    const sur      = parseFloat(document.querySelector('#editLoteModal [name="med_sur"]').value) || 0;
    const oriente  = parseFloat(document.querySelector('#editLoteModal [name="med_oriente"]').value) || 0;
    const poniente = parseFloat(document.querySelector('#editLoteModal [name="med_poniente"]').value) || 0;

    if ((norte > 0 || sur > 0) && (oriente > 0 || poniente > 0)) {
        const anchoPromedio = (norte + sur) / 2;
        const largoPromedio = (oriente + poniente) / 2;
        const superficie = anchoPromedio * largoPromedio;

        document.getElementById('edit_metros_cuadrados').value =
            superficie > 0 ? superficie.toFixed(2) : '';
    }
}


</script>
