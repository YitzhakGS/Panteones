<div class="modal fade" id="showFinadoModal" tabindex="-1"
     aria-labelledby="showFinadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="showFinadoModalLabel">
                        <i class="bi bi-person-vcard me-2 text-muted"></i>Detalle del Finado
                    </h5>
                    <p class="text-muted small mb-0">Información registrada del finado</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <input type="hidden" id="show_id">

            <div class="modal-body pt-3">

                {{-- SECCIÓN 1: IDENTIFICACIÓN --}}
                <div class="section-block mb-3">
                    <span class="section-label">Datos del finado</span>
                    <div class="row g-2 mt-1">
                        <div class="col-md-8">
                            <p class="text-muted small mb-0">Nombre</p>
                            <p id="show_nombre" class="fw-semibold mb-0"></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-0">Sexo</p>
                            <p id="show_sexo" class="fw-semibold mb-0"></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-0">Fecha de defunción</p>
                            <p id="show_fecha_defuncion" class="fw-semibold mb-0"></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-0">Estado actual</p>
                            <p id="show_estado" class="fw-semibold mb-0"></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-0">Tipo construcción</p>
                            <p id="show_tipo_construccion" class="fw-semibold mb-0"></p>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 2: UBICACIÓN --}}
                <div class="section-block mb-3">
                    <span class="section-label">Ubicación actual</span>
                    <div class="row g-2 mt-1">
                        <div class="col-12">
                            <p class="text-muted small mb-0">Ubicación en el panteón</p>
                            <p id="show_ubicacion" class="fw-semibold mb-0"></p>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 3: HISTORIAL --}}
                <div class="section-block mb-3">
                    <span class="section-label">Historial de movimientos</span>
                    <div id="show_movimientos_container">
                        {{-- Se llena con JS --}}
                    </div>
                </div>

                {{-- SECCIÓN 4: OBSERVACIONES --}}
                <div class="section-block mb-3">
                    <span class="section-label">Observaciones</span>
                    <p id="show_observaciones" class="fw-semibold mb-0"></p>
                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-end gap-2 mt-2">

                    <button type="button" class="btn btn-outline-danger"
                            onclick="confirmarEliminacionFinado()">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>

                    <button type="button" class="btn bg-base text-white px-4"
                            id="btnEditarFinado">
                        <i class="bi bi-pencil-square me-1"></i>Editar
                    </button>

                </div>

            </div>
        </div>
    </div>
</div>

{{-- FORM DELETE --}}
<form id="deleteFinadoForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@include('finados.edit')

<script>
function confirmarEliminacionFinado() {
    const id = document.getElementById('show_id').value;

    Swal.fire({
        title: '¿Eliminar finado?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteFinadoForm');
            form.action = `/finados/${id}`;
            form.submit();
        }
    });
}

// EDITAR
document.getElementById('btnEditarFinado').addEventListener('click', function () {

    const id = document.getElementById('show_id').value;

    bootstrap.Modal.getInstance(document.getElementById('showFinadoModal')).hide();

    const form = document.getElementById('editFinadoForm');
    form.action = `/finados/${id}`;

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nombre').value = finadoActual.nombre || '';
    document.getElementById('edit_apellido_paterno').value = finadoActual.apellidoPaterno || '';
    document.getElementById('edit_apellido_materno').value = finadoActual.apellidoMaterno || '';
    document.getElementById('edit_observaciones').value = finadoActual.observaciones || '';

    // ✅ Convertir fecha de d/m/Y a Y-m-d para el input date
    const partes = (finadoActual.fecha_defuncion || '').split('/');
    const fechaISO = partes.length === 3 ? `${partes[2]}-${partes[1]}-${partes[0]}` : '';
    document.getElementById('edit_fecha_defuncion').value = window.finadoActual.fecha_defuncion_iso || fechaISO || '';

    // ✅ Selects — forzar con setTimeout para asegurar que el modal ya abrió
    setTimeout(() => {
        document.getElementById('edit_sexo').value = finadoActual.sexo || '';
        document.getElementById('edit_tipo_construccion').value = finadoActual.tipoConstruccion || '';
    }, 100);

    // ✅ Limpiar sección de movimiento al abrir
    document.getElementById('edit_tipo_movimiento').value = '';
    document.getElementById('edit_fecha_movimiento').value = '';
    document.getElementById('edit_solicitante').value = '';
    document.getElementById('edit_obs_movimiento').value = '';
    const wrapper = document.getElementById('edit_concesion_wrapper');
    if (wrapper) {
        wrapper.classList.add('d-none');
    }

    window.abrirModalEdit();
});
</script>