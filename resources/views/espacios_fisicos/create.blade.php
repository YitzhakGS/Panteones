<div class="modal fade"
     id="createEspacioFisicoModal"
     tabindex="-1"
     aria-labelledby="createEspacioFisicoModalLabel"
     aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title" id="createEspacioFisicoModalLabel">
                    Nuevo Espacio Físico
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <!-- FORM -->
            <form action="{{ route('espacios_fisicos.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <!-- CUADRILLA -->
                    <div class="mb-3">
                        <label for="id_cuadrilla" class="form-label">Cuadrilla</label>

                        <select
                            name="id_cuadrilla"
                            id="id_cuadrilla"
                            class="form-select @error('id_cuadrilla') is-invalid @enderror"
                            required
                        >
                            <option value="">Seleccione una cuadrilla</option>

                            @foreach ($cuadrillas as $cuadrilla)
                                <option value="{{ $cuadrilla->id_cuadrilla }}"
                                    {{ old('id_cuadrilla') == $cuadrilla->id_cuadrilla ? 'selected' : '' }}>
                                    {{ $cuadrilla->nombre }} — {{ $cuadrilla->seccion->nombre }}
                                </option>
                            @endforeach
                        </select>

                        @error('id_cuadrilla')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- TIPO ESPACIO FÍSICO -->
                    <div class="mb-3">
                        <label for="id_tipo_espacio_fisico" class="form-label">
                            Tipo de espacio físico
                        </label>
                        <select
                            name="id_tipo_espacio_fisico"
                            id="id_tipo_espacio_fisico"
                            class="form-select @error('id_tipo_espacio_fisico') is-invalid @enderror"
                            required
                        >
                            <option value="">Seleccione un tipo</option>

                            @foreach ($tiposEspacioFisico as $tipo)
                                <option value="{{ $tipo->id_tipo_espacio_fisico }}"
                                    {{ old('id_tipo_espacio_fisico') == $tipo->id_tipo_espacio_fisico ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>

                        @error('id_tipo_espacio_fisico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NOMBRE -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del espacio</label>
                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre') }}"
                            required
                        >

                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea
                            name="descripcion"
                            id="descripcion"
                            class="form-control @error('descripcion') is-invalid @enderror"
                            rows="3"
                        >{{ old('descripcion') }}</textarea>

                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn bg-base text-white">
                        Guardar
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
