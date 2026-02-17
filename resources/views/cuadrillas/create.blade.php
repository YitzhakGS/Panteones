<div class="modal fade" id="createCuadrillaModal" tabindex="-1" aria-labelledby="createCuadrillaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="createCuadrillaModalLabel">
                    Nueva Cuadrilla
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('cuadrillas.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <!-- SECCIÓN -->
                    <div class="mb-3">
                        <label for="id_seccion" class="form-label">Sección</label>
                        <select
                            name="id_seccion"
                            id="id_seccion"
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

                    <!-- NOMBRE CUADRILLA -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la cuadrilla</label>
                        <input
                            type="text"
                            class="form-control @error('nombre') is-invalid @enderror"
                            id="nombre"
                            name="nombre"
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
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
