<div class="modal fade" id="createLoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-muted"></i>Nuevo Lote
                    </h5>
                    <p class="text-muted small mb-0">Completa los datos para registrar el lote en el sistema</p>
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
                                <input type="text" name="numero"
                                       class="form-control @error('numero') is-invalid @enderror"
                                       value="{{ old('numero') }}" placeholder="Ej. 042" required>
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-rulers me-1 text-muted"></i>Superficie (m²)
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="metros_cuadrados"
                                           id="metros_cuadrados" class="form-control bg-light"
                                           placeholder="0.00" readonly>
                                    <span class="input-group-text text-muted">m²</span>
                                </div>
                                <small class="text-muted" style="font-size: 0.75rem;">Calculado automáticamente</small>
                            </div>
                        </div>
                    </div>

                    {{-- ── SECCIÓN 2: Medidas ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Medidas (metros)</span>
                        <div class="row g-3 mt-1">
                            @foreach(['norte' => 'up', 'sur' => 'down', 'oriente' => 'right', 'poniente' => 'left'] as $dir => $icon)
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-arrow-{{$icon}} me-1"></i>{{ ucfirst($dir) }}
                                </label>
                                <input type="number" step="0.01" name="med_{{$dir}}"
                                       class="form-control measure-input" placeholder="0.00">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── SECCIÓN 3: Colindancias ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Colindancias</span>
                        <div class="row g-3 mt-1">
                            @foreach(['norte', 'sur', 'oriente', 'poniente'] as $dir)
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">
                                    <i class="bi bi-signpost-split me-1"></i>{{ ucfirst($dir) }}
                                </label>
                                <input type="text" name="col_{{$dir}}"
                                       class="form-control" placeholder="Colindancia {{ ucfirst($dir) }}">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── SECCIÓN 4: Ubicación Geográfica ── --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Ubicación del lote</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-layers me-1 text-muted"></i>Sección General
                                </label>
                                <select id="select-seccion-ajax" class="form-select border-primary">
                                    <option value="">-- Seleccione Sección --</option>
                                    @foreach ($secciones as $seccion)
                                        <option value="{{ $seccion->id_seccion }}">
                                            {{ $seccion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>Área / Espacio Físico
                                </label>
                                <select name="id_espacio_fisico"
                                        id="select-espacio-ajax"
                                        class="form-select @error('id_espacio_fisico') is-invalid @enderror"
                                        required disabled>
                                    <option value="">Primero elija una sección...</option>
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
                                      placeholder="Notas, ubicación visual, referencias específicas...">{{ old('referencias') }}</textarea>
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
 * Lógica para la Creación de Lotes (Refactorizada)
 */

/* ==========================================================================
   CÁLCULO DE SUPERFICIE
   ========================================================================== */
document.querySelectorAll('.measure-input').forEach(input => {
    input.addEventListener('input', calcularSuperficie);
});

function calcularSuperficie() {
    // Buscamos los elementos específicamente dentro del modal de creación
    const modal = document.getElementById('createLoteModal');
    
    const norte    = parseFloat(modal.querySelector('[name="med_norte"]').value) || 0;
    const sur      = parseFloat(modal.querySelector('[name="med_sur"]').value) || 0;
    const oriente  = parseFloat(modal.querySelector('[name="med_oriente"]').value) || 0;
    const poniente = parseFloat(modal.querySelector('[name="med_poniente"]').value) || 0;

    // Solo calcular si hay al menos una medida de ancho y una de largo
    if ((norte > 0 || sur > 0) && (oriente > 0 || poniente > 0)) {
        // Usamos la fórmula de área para cuadriláteros irregulares (promedio de lados opuestos)
        const anchoPromedio = (norte + sur) / ( (norte > 0 && sur > 0) ? 2 : 1 );
        const largoPromedio = (oriente + poniente) / ( (oriente > 0 && poniente > 0) ? 2 : 1 );
        
        const superficie = anchoPromedio * largoPromedio;
        
        // Asignamos al input del modal de creación
        document.getElementById('metros_cuadrados').value = superficie > 0 ? superficie.toFixed(2) : '';
    }
}

/* ==========================================================================
   CARGA ASÍNCRONA DE ESPACIOS FÍSICOS POR SECCIÓN
   ========================================================================== */
document.getElementById('select-seccion-ajax').addEventListener('change', function() {
    const idSeccion = this.value;
    const selectEspacio = document.getElementById('select-espacio-ajax');

    selectEspacio.innerHTML = '<option value="">Cargando áreas...</option>';
    selectEspacio.disabled = true;

    if (!idSeccion) {
        selectEspacio.innerHTML = '<option value="">Primero elija una sección...</option>';
        return;
    }

    // El endpoint ahora apunta a secciones
    fetch(`/api/secciones/${idSeccion}/espacios-fisicos`)
        .then(response => {
            if (!response.ok) throw new Error('Error en la carga');
            return response.json();
        })
        .then(data => {
            selectEspacio.innerHTML = '<option value="">-- Seleccione el Área Específica --</option>';
            data.forEach(espacio => {
                const option = document.createElement('option');
                option.value = espacio.id_espacio_fisico; 
                option.textContent = `${espacio.tipo} - ${espacio.nombre}`;
                selectEspacio.appendChild(option);
            });
            selectEspacio.disabled = false;
        })
        .catch(error => {
            console.error('Error AJAX:', error);
            selectEspacio.innerHTML = '<option value="">Error al cargar áreas</option>';
        });
});
</script>