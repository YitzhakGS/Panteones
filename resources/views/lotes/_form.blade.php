<div class="section-block mt-3">
    <span class="section-label">Datos de la nueva Ubicación</span>

    <div class="row g-3 mt-1">

        {{-- NÚMERO --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                <i class="bi bi-hash me-1 text-muted"></i>Número de lote
            </label>
            <input type="text" id="lote_numero" class="form-control" placeholder="Ej. 042">
        </div>

        {{-- SUPERFICIE --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                <i class="bi bi-rulers me-1 text-muted"></i>Superficie (m²)
            </label>
            <div class="input-group">
                <input type="number" step="0.01" id="lote_metros_cuadrados"
                       class="form-control bg-light" readonly>
                <span class="input-group-text text-muted">m²</span>
            </div>
            <small class="text-muted" style="font-size: 0.75rem;">
                Calculado automáticamente
            </small>
        </div>

    </div>
</div>

{{-- MEDIDAS --}}
<div class="section-block mt-3">
    <span class="section-label">Medidas (metros)</span>

    <div class="row g-3 mt-1">

        @foreach(['norte' => 'up', 'sur' => 'down', 'oriente' => 'right', 'poniente' => 'left'] as $dir => $icon)
        <div class="col-md-3">
            <label class="form-label fw-semibold text-muted small">
                <i class="bi bi-arrow-{{ $icon }} me-1"></i>{{ ucfirst($dir) }}
            </label>
            <input type="number" step="0.01"
                   id="lote_med_{{ $dir }}"
                   class="form-control lote-measure-input"
                   placeholder="0.00">
        </div>
        @endforeach

    </div>
</div>

{{-- COLINDANCIAS --}}
<div class="section-block mt-3">
    <span class="section-label">Colindancias</span>

    <div class="row g-3 mt-1">

        @foreach(['norte', 'sur', 'oriente', 'poniente'] as $dir)
        <div class="col-md-6">
            <label class="form-label fw-semibold text-muted small">
                <i class="bi bi-signpost-split me-1"></i>{{ ucfirst($dir) }}
            </label>
            <input type="text"
                   id="lote_col_{{ $dir }}"
                   class="form-control"
                   placeholder="Colindancia {{ ucfirst($dir) }}">
        </div>
        @endforeach

    </div>
</div>

{{-- UBICACIÓN --}}
<div class="section-block mt-3">
    <span class="section-label">Ubicación del lote</span>

    <div class="row g-3 mt-1">

        <div class="col-md-6">
            <label class="form-label fw-semibold">
                <i class="bi bi-layers me-1 text-muted"></i>Sección General
            </label>
            <select id="lote_seccion" class="form-select">
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
            <select id="lote_espacio" class="form-select">
                <option value="">Primero elija una sección...</option>
            </select>
        </div>

    </div>
</div>

{{-- REFERENCIAS --}}
<div class="section-block mt-3">
    <span class="section-label">Referencias adicionales</span>

    <textarea id="lote_referencias"
              rows="2"
              class="form-control"
              placeholder="Notas, ubicación visual, referencias específicas..."></textarea>
</div>