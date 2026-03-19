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

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-bookmarks me-1 text-muted"></i>Uso funerario
                            </label>
                            <input type="text" id="show_uso"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tag me-1 text-muted"></i>Tipo
                            </label>
                            <input type="text" id="show_tipo"
                                   class="form-control bg-light border-0 text-capitalize" readonly>
                        </div>

                        <div class="col-md-4">
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

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-1 text-muted"></i>Fecha de inicio
                            </label>
                            <input type="text" id="show_fecha_inicio"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-x me-1 text-muted"></i>Fecha de vencimiento
                            </label>
                            <input type="text" id="show_fecha_fin"
                                   class="form-control bg-light border-0" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-exclamation-triangle me-1 text-muted"></i>Años en adeudo
                            </label>
                            <input type="text" id="show_anos_adeudo"
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
                                    id="btnEditarConcesion">
                                <i class="bi bi-pencil-square me-1"></i>Editar
                            </button>
                            <button type="button"
                                    class="btn btn-outline-warning w-100"
                                    id="btnCancelarConcesion">
                                <i class="bi bi-slash-circle me-1"></i>Cancelar concesión
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
const estatusColores = {
    'Al Corriente': '#15803d',
    'Con Adeudo':   '#b91c1c',
    'Activa':       '#1d4ed8',
    'Inactiva':     '#475569',
    'Cancelada':    '#92400e',
};

// Rellenar modal show desde la card
document.addEventListener('DOMContentLoaded', function () {
    const showModal = document.getElementById('showConcesionModal');
    if (!showModal) return;

    showModal.addEventListener('show.bs.modal', function (event) {
        const card = event.relatedTarget;
        if (!card) return;

        // 1. Obtener el estatus de la card (ajusta 'cancelada' según cómo venga de tu BD)
        const estatusNombre = card.dataset.estatus ?? '';
        const btnCancelar = document.getElementById('btnCancelarConcesion');

        // 2. Lógica para ocultar/mostrar el botón
        // Usamos toLowerCase() para evitar problemas con mayúsculas/minúsculas
        if (estatusNombre.toLowerCase() === 'cancelada') {
            btnCancelar.classList.add('d-none'); // Oculta el botón usando Bootstrap
        } else {
            btnCancelar.classList.remove('d-none'); // Lo muestra si no está cancelada
        }

        document.getElementById('show_id_concesion').value   = card.dataset.id            ?? '';
        document.getElementById('show_lote').value           = card.dataset.lote           ?? '';
        document.getElementById('show_titular').value        = card.dataset.titular        ?? '';
        document.getElementById('show_uso').value            = card.dataset.uso            ?? '';
        document.getElementById('show_tipo').value           = card.dataset.tipo           ?? '';
        document.getElementById('show_fecha_inicio').value   = card.dataset.inicio         ?? '';
        document.getElementById('show_fecha_fin').value      = card.dataset.fin            ?? 'Indefinida';
        document.getElementById('show_anos_adeudo').value    = card.dataset.anosAdeudo     ?? '0';
        document.getElementById('show_observaciones').value  = card.dataset.observaciones  ?? '';

        const estatusInput = document.getElementById('show_estatus');
        estatusInput.value            = estatusNombre;
        estatusInput.style.color      = estatusColores[estatusNombre] ?? '#475569';
        estatusInput.style.fontWeight = estatusColores[estatusNombre] ? '700' : '400';
    });
});

// Eliminar
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
            form.action   = `/concesiones/${id}`;
            form.method   = 'POST';
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Cancelar concesión
document.getElementById('btnCancelarConcesion').addEventListener('click', function () {
    const id = document.getElementById('show_id_concesion').value;

    Swal.fire({
        title: '¿Cancelar concesión?',
        text: 'La concesión quedará marcada como cancelada sin eliminarse.',
        icon: 'warning',
        // Se eliminó la línea de input: 'textarea'
        showCancelButton: true,
        confirmButtonColor: '#d97706',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'Volver',
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.action = `/concesiones/${id}/cancelar`;
            form.method = 'POST';
            form.innerHTML = `
                @csrf
                @method('PATCH')
            `; // Se eliminó el input oculto de observaciones
            document.body.appendChild(form);
            form.submit();
        }
    });
});


// Editar — abre modal con datos del servidor
document.getElementById('btnEditarConcesion').addEventListener('click', function () {
    const id = document.getElementById('show_id_concesion').value;

    const modalShowEl = document.getElementById('showConcesionModal');
    const modalShow   = bootstrap.Modal.getInstance(modalShowEl);
    if (modalShow) modalShow.hide();

    fetch(`/concesiones/${id}/data`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => { if (!r.ok) throw new Error(); return r.json(); })
    .then(data => {
        const form = document.getElementById('editConcesionForm');
        form.action = `/concesiones/${data.id_concesion}`;

        document.getElementById('edit_id_concesion').value   = data.id_concesion;
        document.getElementById('edit_lote').value           = data.id_lote;
        document.getElementById('edit_titular').value        = data.id_titular;
        document.getElementById('edit_uso').value            = data.id_uso_funerario;
        document.getElementById('edit_tipo').value           = data.tipo;
        document.getElementById('edit_fecha').value          = data.fecha_inicio;
        document.getElementById('edit_observaciones').value  = data.observaciones ?? '';

        // Marcar el radio correcto
        document.querySelector(`input[name="tipo"][value="${data.tipo}"]`).checked = true;

        new bootstrap.Modal(document.getElementById('editConcesionModal')).show();
    })
    .catch(() => Swal.fire('Error', 'No se pudieron cargar los datos para editar', 'error'));
});
</script>