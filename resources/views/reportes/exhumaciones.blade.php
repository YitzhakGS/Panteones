@extends('layouts.app')



@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-3 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-arrow-up-square"></i> Reporte de Exhumaciones
            </h4>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">

        {{-- Barra superior --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small">
                {{ $datos->count() }} registro(s) encontrados
            </span>
            <button class="btn bg-base text-white" data-bs-toggle="modal" data-bs-target="#filtrosExhumacionesModal">
                <i class="bi bi-funnel me-1"></i> Filtrar / Exportar PDF
            </button>
        </div>

        {{-- Tabla --}}
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle text-center" id="tablaExhumaciones">
                <thead class="table-dark">
                    <tr>
                        <th>DÍA</th>
                        <th>MES</th>
                        <th>AÑO</th>
                        <th>NOMBRE DE LA PERSONA EXHUMADA</th>
                        <th>UBICACIÓN EN EL PANTEÓN</th>
                        <th>FECHA DE DEFUNCIÓN</th>
                        <th>NOMBRE DEL SOLICITANTE</th>
                        <th>UBICACIÓN NUEVA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($datos as $row)
                        <tr>
                            <td>{{ $row['dia'] }}</td>
                            <td>{{ $row['mes'] }}</td>
                            <td>{{ $row['anio'] }}</td>
                            <td class="text-start">{{ $row['nombre_finado'] }}</td>
                            <td class="text-start">{{ $row['ubicacion_panteon'] }}</td>
                            <td>{{ $row['fecha_defuncion'] }}</td>
                            <td class="text-start">{{ $row['solicitante'] }}</td>
                            <td class="text-start">{{ $row['ubicacion_nueva'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No hay exhumaciones registradas con los filtros actuales.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $datos->links() }}
        </div>

    </div>
</div>

{{-- MODAL FILTROS / PDF --}}
<div class="modal fade" id="filtrosExhumacionesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-funnel me-2 text-muted"></i>Filtrar reporte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('reportes.exhumaciones') }}" id="formFiltrosExhumaciones">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fecha inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control"
                                   value="{{ $filtros['fecha_inicio'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fecha fin</label>
                            <input type="date" name="fecha_fin" class="form-control"
                                   value="{{ $filtros['fecha_fin'] ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros('formFiltrosExhumaciones', '{{ route('reportes.exhumaciones') }}')">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </button>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn bg-base text-white">
                            <i class="bi bi-search me-1"></i>Aplicar
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="imprimirTabla()">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Exportar PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function limpiarFiltros(formId, url) {
    window.location.href = url;
}

function imprimirTabla() {
    const contenido = document.getElementById('tablaExhumaciones').outerHTML;
    const titulo    = 'Reporte de Exhumaciones';

    const ventana = window.open('', '_blank');
    ventana.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>${titulo}</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 11px; padding: 20px; }
                h2   { text-align: center; margin-bottom: 16px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #000; padding: 4px 6px; text-align: left; }
                thead { background-color: #222; color: #fff; }
                @media print { button { display: none; } }
            </style>
        </head>
        <body>
            <h2>${titulo}</h2>
            ${contenido}
            <script>window.onload = () => { window.print(); }<\/script>
        </body>
        </html>
    `);
    ventana.document.close();
}
</script>
@endpush