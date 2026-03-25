<div class="modal fade" id="createBeneficiarioModal" tabindex="-1"
     aria-labelledby="createBeneficiarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="createBeneficiarioModalLabel">
                        <i class="bi bi-person-plus me-2 text-muted"></i>Nuevo Beneficiario
                    </h5>
                    <p class="text-muted small mb-0">Completa los datos para registrar el beneficiario</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('beneficiarios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body pt-3">

                    {{-- SECCIÓN 0: Titular --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Titular asociado</span>
                        <div class="row g-3 mt-1">
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person me-1 text-muted"></i>
                                    Titular <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <select id="select-titular" name="id_titular" required>
                                        <option value=""></option>
                                        @foreach($titulares as $titular)
                                            <option value="{{ $titular->id_titular }}" >
                                                {{ $titular->familia }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="button" class="input-group-text" id="btnTitularBeneficiario">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </div>

                                @error('id_titular')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 1: Identificación --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Identificación y Contacto</span>
                        <div class="row g-3 mt-1">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person me-1 text-muted"></i>
                                    Nombre del beneficiario <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre') }}"
                                    placeholder="Nombre completo" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-telephone me-1 text-muted"></i>
                                    Teléfono
                                </label>
                                <input type="text" name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono') }}"
                                    placeholder="Opcional">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 2: Domicilio --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Domicilio</span>
                        <div class="row g-3 mt-1">

                            <div class="col-md-7">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-house me-1 text-muted"></i>
                                    Calle y número <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="domicilio"
                                    class="form-control @error('domicilio') is-invalid @enderror"
                                    value="{{ old('domicilio') }}"
                                    required>
                                @error('domicilio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-signpost me-1 text-muted"></i>
                                    Colonia <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="colonia"
                                    class="form-control @error('colonia') is-invalid @enderror"
                                    value="{{ old('colonia') }}" required>
                                @error('colonia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-mailbox me-1 text-muted"></i>
                                    C.P. <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="codigo_postal"
                                    class="form-control @error('codigo_postal') is-invalid @enderror"
                                    value="{{ old('codigo_postal') }}"
                                    maxlength="5" required>
                                @error('codigo_postal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-building me-1 text-muted"></i>
                                    Municipio <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="municipio"
                                    class="form-control @error('municipio') is-invalid @enderror"
                                    value="{{ old('municipio') }}" required>
                                @error('municipio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-map me-1 text-muted"></i>
                                    Estado <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="estado"
                                    class="form-control @error('estado') is-invalid @enderror"
                                    value="{{ old('estado') }}" required>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- DOCUMENTOS --}}
                    @if($tiposDocumento->isNotEmpty())
                        <div class="section-block mb-1">
                            <span class="section-label">
                                Documentos <span class="text-muted fw-normal">(opcional)</span>
                            </span>

                            <div class="row g-3 mt-1">
                                @foreach($tiposDocumento as $tipo)
                                <div class="{{ $tiposDocumento->count() === 1 ? 'col-12' : 'col-md-6' }}">
                                    
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-file-earmark me-1 text-muted"></i>
                                        {{ $tipo->nombre }}
                                    </label>

                                    <input type="file"
                                        name="documentos[{{ $tipo->id_tipo_documento }}][archivo]"
                                        class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">

                                    <input type="hidden"
                                        name="documentos[{{ $tipo->id_tipo_documento }}][id_tipo_documento]"
                                        value="{{ $tipo->id_tipo_documento }}">

                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    {{-- BOTONES --}}
                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn bg-base text-white px-4">
                            Guardar beneficiario
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    let tsTitularBeneficiario = null;

    const tsConfig = {
        openOnFocus: false,
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: "Buscar titular...",
        allowEmptyOption: true,
        maxOptions: 4,
        onInitialize: function() {
            // 🔥 mismo hack que ya usas
            this.wrapper.style.setProperty('width', '85%', 'important');
        }
    };

    // Inicializar UNA vez
    if (!tsTitularBeneficiario) {
        tsTitularBeneficiario = new TomSelect("#select-titular", tsConfig);

        // Botón abre dropdown
        document.getElementById('btnTitularBeneficiario').addEventListener('click', () => {
            tsTitularBeneficiario.open();
        });
    }

    // 🔥 Auto-rellenado (igual que ya tenías)
    tsTitularBeneficiario.on('change', function(value) {
        if (!value || value === "") return;

        const originalOption = document.querySelector(`#select-titular option[value="${value}"]`);
        
        if (originalOption) {
            const modalForm = document.getElementById('createBeneficiarioModal');

            modalForm.querySelector('input[name="domicilio"]').value = originalOption.dataset.domicilio || '';
            modalForm.querySelector('input[name="colonia"]').value = originalOption.dataset.colonia || '';
            modalForm.querySelector('input[name="codigo_postal"]').value = originalOption.dataset.cp || '';
            modalForm.querySelector('input[name="municipio"]').value = originalOption.dataset.municipio || '';
            modalForm.querySelector('input[name="estado"]').value = originalOption.dataset.estado || '';
        }
    });

    // Modal create
    const modalCreate = document.getElementById('createBeneficiarioModal');

    if (modalCreate) {
        modalCreate.addEventListener('shown.bs.modal', function() {
            tsTitularBeneficiario.clear(true);
            tsTitularBeneficiario.refreshState();
        });
    }

});
</script>