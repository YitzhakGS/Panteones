<div class="modal fade" id="showConcesionModal" tabindex="-1"
     aria-labelledby="showConcesionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="showConcesionModalLabel">
                        <i class="bi bi-file-earmark-text me-2 text-muted"></i>Detalle de la Concesión
                    </h5>
                    <p class="text-muted small mb-0">Información registrada de la concesión</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <input type="hidden" id="show_id_concesion">

            {{-- BODY --}}
            <div class="modal-body pt-3">

                {{-- ── SECCIÓN 1: Datos generales ── --}}
                <div class="section-block mb-3">
                    <span class="section-label">Datos generales</span>
                    <div class="row g-3 mt-1">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-geo-alt me-1 text-muted"></i>Lote
                            </label>
                            <input type="text" id="show_lote"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person me-1 text-muted"></i>Titular
                            </label>
                            <input type="text" id="show_titular"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                    </div>
                </div>

                {{-- ── SECCIÓN 2: Concesión ── --}}
                <div class="section-block mb-3">
                    <span class="section-label">Concesión</span>
                    <div class="row g-3 mt-1">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-bookmarks me-1 text-muted"></i>Uso funerario
                            </label>
                            <input type="text" id="show_uso"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-circle-fill me-1 text-muted" style="font-size:.5rem; vertical-align:middle"></i>Estatus
                            </label>
                            <input type="text" id="show_estatus"
                                   class="form-control bg-light border-0 fw-bold" readonly>
                        </div>

                    </div>
                </div>

                {{-- ── SECCIÓN 3: Fechas ── --}}
                <div class="section-block mb-3">
                    <span class="section-label">Fechas</span>
                    <div class="row g-3 mt-1">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-1 text-muted"></i>Fecha de inicio
                            </label>
                            <input type="text" id="show_fecha_inicio"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-x me-1 text-muted"></i>Próximo vencimiento
                            </label>
                            <input type="text" id="show_fecha_fin"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                    </div>
                </div>

                {{-- ── SECCIÓN 4: Observaciones + Botones ── --}}
                <div class="section-block mb-1">
                    <span class="section-label">Observaciones</span>
                    <div class="row g-3 mt-1 align-items-end">

                        <div class="col-md-8">
                            <textarea id="show_observaciones" rows="2"
                                      class="form-control bg-light border-0" readonly
                                      placeholder="Sin observaciones adicionales..."></textarea>
                        </div>

                        <div class="col-md-4 d-flex flex-column gap-2">
                            <button type="button"
                                    class="btn bg-base text-white w-100"
                                    id="btnEditarConcesion"
                                    data-bs-target="#editConcesionModal"
                                    data-bs-toggle="modal">
                                <i class="bi bi-pencil-square me-1"></i>Editar
                            </button>
                            <button type="button"
                                    class="btn btn-outline-danger w-100"
                                    onclick="confirmarEliminacionConcesion()">
                                <i class="bi bi-trash me-1"></i>Eliminar
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('concesiones.edit')


<script>
function confirmarEliminacionConcesion() {
    const id = document.getElementById('show_id_concesion').value;

    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede revertir.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.action  = `/concesiones/${id}`;
            form.method  = 'POST';
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}



document.getElementById('btnEditarConcesion').addEventListener('click', function () {
    const id = document.getElementById('show_id_concesion').value;

    // 1. Ocultar el modal de detalle (Ver)
    const modalShowEl = document.getElementById('showConcesionModal');
    const modalShow = bootstrap.Modal.getInstance(modalShowEl);
    if (modalShow) modalShow.hide();

    // 2. Pedir los datos al servidor
    // Agregamos el header 'X-Requested-With' para que Laravel detecte que es AJAX
    fetch(`/concesiones/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Error al obtener datos');
        return response.json();
    })
    .then(data => {
        // 3. Llenar los campos del modal de Edición con los datos del JSON
        const form = document.getElementById('editConcesionForm');
        form.action = `/concesiones/${data.id_concesion}`;// Actualiza la ruta del form

        document.getElementById('edit_id_concesion').value = data.id_concesion;
        document.getElementById('edit_lote').value = data.id_lote;
        document.getElementById('edit_titular').value = data.id_titular;
        document.getElementById('edit_uso').value = data.id_uso_funerario;
        document.getElementById('edit_fecha').value = data.fecha_inicio;
        document.getElementById('edit_monto').value = data.monto;
        document.getElementById('edit_observaciones').value = data.observaciones;

        // 4. Abrir el modal de edición
        const modalEdit = new bootstrap.Modal(document.getElementById('editConcesionModal'));
        modalEdit.show();
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'No se pudieron cargar los datos para editar', 'error');
    });
});
</script>