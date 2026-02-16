<div class="modal fade" id="editSeccionModal" tabindex="-1" aria-labelledby="editSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSeccionModalLabel">Editar Sección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSeccionForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_seccion" id="edit_id_seccion" value="{{ old('id_seccion') }}">
                <!-- campos del formulario (ej. nombre) -->
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre">Nombre</label>
                        <input  type="text"
                                name="nombre"
                                id="edit_nombre"
                                class="form-control" 
                                required>
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn bg-base text-white">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>