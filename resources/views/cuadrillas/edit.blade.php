<div class="modal fade" id="editCuadrillaModal" tabindex="-1" aria-labelledby="editCuadrillaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editCuadrillaModalLabel">
                    Editar Cuadrilla
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editCuadrillaForm" method="POST">
                @csrf
                @method('PUT')

                <!-- id oculto -->
                <input type="hidden"
                       name="id_cuadrilla"
                       id="edit_id_cuadrilla"
                       value="{{ old('id_cuadrilla') }}">

                <div class="modal-body">

                    <!-- SECCIÓN -->
                    <div class="mb-3">
                        <label for="edit_id_seccion" class="form-label">Sección</label>
                        <select
                            name="id_seccion"
                            id="edit_id_seccion"
                            class="form-select @error('id_seccion') is-invalid @enderror"
                            required
                        >
                            <option value="">Seleccione una sección</option>

                            @foreach ($secciones as $seccion)
                                <option value="{{ $seccion->id_seccion }}"
                                    {{ old('id_seccion') == $seccion->id_seccion ? 'selected' : '' }}>
                                    {{ $seccion->nombre }}
                                </option>
                            @endforeach
                        </select>

                        @error('id_seccion')
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
                            value="{{ old('nombre') }}"
                            required
                        >

                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

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
