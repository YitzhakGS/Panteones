<div class="modal fade" id="createFinadoModal" tabindex="-1"
     aria-labelledby="createFinadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="createFinadoModalLabel">
                        <i class="bi bi-person-plus me-2 text-muted"></i>Nuevo Finado
                    </h5>
                    <p class="text-muted small mb-0">
                        Completa los datos para registrar el finado
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('finados.store') }}" method="POST">
                @csrf

                <div class="modal-body pt-3">

                    {{-- SECCIÓN 1: DATOS GENERALES --}}
                    <div class="section-block mb-3">
                        <span class="section-label">
                            <i class="bi bi-person me-1"></i> Datos del finado
                        </span>

                        <div class="row g-3 mt-1">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Nombre(s) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre') }}"
                                    placeholder="Ej. Juan Carlos"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Apellido paterno
                                </label>
                                <input type="text" name="apellido_paterno"
                                    class="form-control"
                                    value="{{ old('apellido_paterno') }}"
                                    placeholder="Ej. García">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Apellido materno
                                </label>
                                <input type="text" name="apellido_materno"
                                    class="form-control"
                                    value="{{ old('apellido_materno') }}"
                                    placeholder="Ej. López">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Fecha de fallecimiento
                                </label>
                                <input type="date" name="fecha_defuncion"
                                    class="form-control"
                                    value="{{ old('fecha_defuncion') }}">
                                <small class="text-muted">¿Cuándo falleció?</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Sexo
                                </label>
                                <select name="sexo" class="form-control">
                                    <option value="">Seleccionar</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Tipo de construcción
                                </label>
                                <select name="tipo_construccion" class="form-control">
                                    <option value="">Sin construcción</option>
                                    <option value="cripta">Cripta</option>
                                    <option value="capilla">Capilla</option>
                                </select>
                                <small class="text-muted">Tipo de obra en la concesión, si aplica</small>
                            </div>

                            {{-- ✅ SOLICITANTE --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-check me-1 text-muted"></i>
                                    Solicitante del registro
                                </label>
                                <input type="text" name="solicitante"
                                    class="form-control"
                                    value="{{ old('solicitante') }}"
                                    placeholder="Ej. María González — familiar directo">
                                <small class="text-muted">
                                    Persona que solicita el registro del finado (familiar, representante legal, etc.)
                                </small>
                            </div>

                        </div>
                    </div>

                    {{-- SECCIÓN 2: INHUMACIÓN INICIAL --}}
                    <div class="section-block mb-3">
                        <span class="section-label">
                            <i class="bi bi-geo-alt me-1"></i> Inhumación inicial
                            <span class="text-muted fw-normal">(opcional — puedes registrarla después)</span>
                        </span>

                        <div class="row g-3 mt-1">

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    Concesión destino
                                </label>
                                <div class="input-group">
                                    <select id="select-concesion" name="id_ubicacion_actual">
                                        <option value=""></option>
                                        @foreach($concesiones as $c)
                                            {{-- AHORA --}}
                                            @php
                                                $familia = $c->titular?->familia ?? '—';
                                                $seccion = optional($c->lote?->espaciosActuales->first()?->seccion)->nombre ?? '—';
                                                $numero  = $c->lote?->numero ?? '—';
                                            @endphp
                                            <option value="{{ $c->id_concesion }}">
                                                {{ $familia }} — {{ $seccion }}, Lote {{ $numero }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    <button type="button" class="input-group-text" id="btnConcesion">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>

                                    <small class="text-muted d-block mb-1">Lugar donde será depositado el finado</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Fecha de inhumación
                                </label>
                                <input type="date" name="fecha" class="form-control">
                                <small class="text-muted">¿Cuándo se realizó o realizará el entierro?</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    Observaciones
                                </label>
                                <textarea name="observaciones"
                                    class="form-control"
                                    rows="1"
                                    placeholder="Notas adicionales sobre la inhumación (opcional)..."></textarea>
                            </div>

                        </div>
                    </div>
                    {{-- BOTONES --}}
                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn bg-base text-white px-4">
                            Guardar finado
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    let tsConcesion = null;

    const tsConfig = {
        openOnFocus: false,
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: "Buscar concesión...",
        allowEmptyOption: true,
        maxOptions: 5,
        onInitialize: function() {
            this.wrapper.style.setProperty('width', '85%', 'important');
        }
    };

    if (!tsConcesion) {
        tsConcesion = new TomSelect("#select-concesion", tsConfig);

        document.getElementById('btnConcesion').addEventListener('click', () => {
            tsConcesion.open();
        });
    }

    const modalCreate = document.getElementById('createFinadoModal');

    if (modalCreate) {
        modalCreate.addEventListener('shown.bs.modal', function() {
            tsConcesion.clear(true);
            tsConcesion.refreshState();
        });
    }

});
</script>