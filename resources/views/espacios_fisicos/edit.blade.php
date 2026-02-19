<div class="modal fade"
     id="editEspacioFisicoModal"
     tabindex="-1"
     aria-labelledby="editEspacioFisicoModalLabel"
     aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title" id="editEspacioFisicoModalLabel">
                    Editar Espacio Físico
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <!-- FORM -->
            <form id="editEspacioFisicoForm" method="POST">
                @csrf
                @method('PUT')

                <!-- ID oculto -->
                <input type="hidden"
                       name="id_espacio_fisico"
                       id="edit_id_espacio_fisico">

                <div class="modal-body">

                    <!-- CUADRILLA -->
                    <div class="mb-3">
                        <label for="edit_id_cuadrilla" class="form-label">Cuadrilla</label>
                        <select
                            name="id_cuadrilla"
                            id="edit_id_cuadrilla"
                            class="form-select @error('id_cuadrilla') is-invalid @enderror"
                            required
                        >
                            <option value="">Seleccione una cuadrilla</option>

                            @foreach ($cuadrillas as $cuadrilla)
                                <option value="{{ $cuadrilla->id_cuadrilla }}">
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
                        <label for="edit_id_tipo_espacio_fisico" class="form-label">
                            Tipo de espacio físico
                        </label>
                        <select
                            name="id_tipo_espacio_fisico"
                            id="edit_id_tipo_espacio_fisico"
                            class="form-select @error('id_tipo_espacio_fisico') is-invalid @enderror"
                            required
                        >
                            <option value="">Seleccione un tipo</option>

                            @foreach ($tiposEspacioFisico as $tipo)
                                <option value="{{ $tipo->id_tipo_espacio_fisico }}">
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
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input
                            type="text"
                            name="nombre"
                            id="edit_nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            required
                        >

                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea
                            name="descripcion"
                            id="edit_descripcion"
                            class="form-control @error('descripcion') is-invalid @enderror"
                            rows="3">
                        </textarea>

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
                        Actualizar
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
