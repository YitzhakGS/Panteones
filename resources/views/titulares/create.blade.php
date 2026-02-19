<div class="modal fade" id="createTitularModal" tabindex="-1"
     aria-labelledby="createTitularModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="createTitularModalLabel">
                    <i class="bi bi-person-plus"></i> Nuevo Titular
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Form --}}
            <form action="{{ route('titulares.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="row">
                        {{-- Familia --}}
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Familia / Titular <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                name="familia"
                                class="form-control @error('familia') is-invalid @enderror"
                                value="{{ old('familia') }}"
                                placeholder="Ej. García Pérez (o nombre del titular)"
                                required>
                            @error('familia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Domicilio --}}
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Domicilio <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                name="domicilio"
                                class="form-control @error('domicilio') is-invalid @enderror"
                                value="{{ old('domicilio') }}"
                                placeholder="Calle, número, manzana, lote"
                                required>
                            @error('domicilio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- Colonia --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Colonia <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="colonia"
                                   class="form-control @error('colonia') is-invalid @enderror"
                                   value="{{ old('colonia') }}"
                                   required>
                            @error('colonia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Código Postal --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                C.P. <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="codigo_postal"
                                   class="form-control @error('codigo_postal') is-invalid @enderror"
                                   value="{{ old('codigo_postal') }}"
                                   maxlength="5"
                                   required>
                            @error('codigo_postal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- Municipio --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Municipio <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="municipio"
                                   class="form-control @error('municipio') is-invalid @enderror"
                                   value="{{ old('municipio') }}"
                                   required>
                            @error('municipio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Estado --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Estado <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="estado"
                                   class="form-control @error('estado') is-invalid @enderror"
                                   value="{{ old('estado') }}"
                                   placeholder="Ej. Hgo"
                                   required>
                            @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Teléfono + botones --}}
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="telefono"
                                   class="form-control @error('telefono') is-invalid @enderror"
                                   value="{{ old('telefono') }}"
                                   placeholder="10 dígitos"
                                   required>
                            @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3 text-end">
                            <button type="button" class="btn btn-outline-secondary me-2"
                                    data-bs-dismiss="modal">
                                Cancelar
                            </button>
                            <button type="submit" class="btn bg-base text-white">
                                <i class="bi bi-save"></i> Guardar
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
