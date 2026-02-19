<div class="modal fade" id="showTitularModal" tabindex="-1"
     aria-labelledby="showTitularModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="showTitularModalLabel">
                    <i class="bi bi-person-vcard"></i>
                    Detalle del Titular
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body (SIN form por ahora) --}}
            <div class="modal-body">

                <input type="hidden" id="show_id">

                <div class="row">
                    {{-- Familia --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Familia / Titular</label>
                        <input type="text" id="show_familia"
                               class="form-control"
                               readonly>
                    </div>

                    {{-- Domicilio --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Domicilio </label>
                        <input type="text" id="show_domicilio"
                               class="form-control"
                               readonly>
                    </div>
                </div>

                <div class="row">
                    {{-- Colonia --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Colonia</label>
                        <input type="text" id="show_colonia"
                               class="form-control"
                               readonly>
                    </div>

                    {{-- Código Postal --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">C.P.</label>
                        <input type="text" id="show_cp"
                               class="form-control"
                               readonly>
                    </div>
                </div>

                <div class="row">
                    {{-- Municipio --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Municipio</label>
                        <input type="text" id="show_municipio"
                               class="form-control"
                               readonly>
                    </div>

                    {{-- Estado --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Estado</label>
                        <input type="text" id="show_estado"
                               class="form-control"
                               readonly>
                    </div>
                </div>

                <div class="row align-items-end">
                    {{-- Teléfono --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" id="show_telefono"
                               class="form-control"
                               readonly>
                    </div>

                    {{-- Botones --}}
                    <div class="col-md-6 mb-3 text-end">
                        <button type="button"
                                class="btn btn-outline-danger me-2"
                                onclick="confirmarEliminacion({{ $titular->id_titular }})">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                        <form id="delete-form-{{ $titular->id_titular }}" 
                            action="{{ route('titulares.destroy', $titular->id_titular) }}" 
                            method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>

                        <button type="button"
                                class="btn bg-base text-white"
                                id="btnEditarTitular">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('titulares.edit')


<script>
function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Eliminar registro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}
</script>

<script>
document.getElementById('btnEditarTitular').addEventListener('click', function () {

    const id = document.getElementById('show_id').value;

    // Cerrar modal show
    bootstrap.Modal.getInstance(
        document.getElementById('showTitularModal')
    ).hide();

    // Set action del form
    const form = document.getElementById('editTitularForm');
    form.action = `/titulares/${id}`;

    // Pasar datos
    document.getElementById('edit_id').value        = id;
    document.getElementById('edit_familia').value   = document.getElementById('show_familia').value;
    document.getElementById('edit_domicilio').value = document.getElementById('show_domicilio').value;
    document.getElementById('edit_colonia').value   = document.getElementById('show_colonia').value;
    document.getElementById('edit_cp').value        = document.getElementById('show_cp').value;
    document.getElementById('edit_municipio').value = document.getElementById('show_municipio').value;
    document.getElementById('edit_estado').value    = document.getElementById('show_estado').value;
    document.getElementById('edit_telefono').value  = document.getElementById('show_telefono').value;

    // Abrir modal edit
    new bootstrap.Modal(document.getElementById('editTitularModal')).show();
});
</script>


