<div class="modal fade" id="createTitularModal" tabindex="-1"
     aria-labelledby="createTitularModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="createTitularModalLabel">
                        <i class="bi bi-person-plus me-2 text-muted"></i>Nuevo Titular
                    </h5>
                    <p class="text-muted small mb-0">Completa los datos para registrar el titular</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('titulares.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body pt-3">

                    {{-- SECCIÓN 1: Identificación --}}
                    <div class="section-block mb-3">
                        <span class="section-label">Identificación y Contacto</span>
                        <div class="row g-3 mt-1">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-people me-1 text-muted"></i>
                                    Familia / Titular <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="familia"
                                    class="form-control @error('familia') is-invalid @enderror"
                                    value="{{ old('familia') }}"
                                    placeholder="Ej. García Pérez" required>
                                @error('familia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-telephone me-1 text-muted"></i>
                                    Teléfono <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono') }}"
                                    placeholder="10 dígitos" required>
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
                                    placeholder="Calle, número, manzana, lote" required>
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
                                    maxlength="5" placeholder="00000" required>
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
                                    value="{{ old('estado') }}"
                                    placeholder="Ej. Hidalgo" required>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- SECCIÓN 4: Documentos --}}
                    @if($tiposDocumentoTitular->isNotEmpty())
                    <div class="section-block mb-1">
                        <span class="section-label">Documentos <span class="text-muted fw-normal">(opcional)</span></span>
                        <div class="row g-3 mt-1">
                            @foreach($tiposDocumentoTitular as $tipo)
                            <div class="{{ $tiposDocumentoTitular->count() === 1 ? 'col-12' : 'col-md-6' }}">
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

                    {{-- Botones --}}
                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            <i class="bi bi-x me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn bg-base text-white px-4">
                            <i class="bi bi-check2-circle me-1"></i>Guardar titular
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>