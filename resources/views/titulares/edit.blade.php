<div class="modal fade" id="editTitularModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form id="editTitularForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square"></i> Editar Titular
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="edit_id" name="id_titular">

                    <div class="mb-3">
                        <label class="form-label">Familia</label>
                        <input type="text" id="edit_familia" name="familia" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Domicilio</label>
                        <input type="text" id="edit_domicilio" name="domicilio" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Colonia</label>
                            <input type="text" id="edit_colonia" name="colonia" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">C.P.</label>
                            <input type="text" id="edit_cp" name="codigo_postal" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Municipio</label>
                            <input type="text" id="edit_municipio" name="municipio" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <input type="text" id="edit_estado" name="estado" class="form-control">
                        </div>
                    </div>

                    {{-- Teléfono + botones (IGUAL A CREATE) --}}
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" id="edit_telefono" name="telefono" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3 text-end">
                            <button type="button"
                                    class="btn btn-outline-secondary me-2"
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

<script>
let formSubmitted = false;

document.getElementById('editTitularForm').addEventListener('submit', () => {
    formSubmitted = true;
});

document.getElementById('editTitularModal')
    .addEventListener('hidden.bs.modal', function () {

        if (!formSubmitted) {
            window.location.href = "{{ route('titulares.index') }}";
        }
    });
</script>
