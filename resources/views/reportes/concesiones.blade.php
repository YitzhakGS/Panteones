@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-3 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="bi bi-file-earmark-text"></i> Reporte de Concesiones / Refrendo
            </h4>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small">
                {{ $datos->count() }} registro(s) encontrados
            </span>
            <button class="btn bg-base text-white" data-bs-toggle="modal" data-bs-target="#filtrosConcesionesModal">
                <i class="bi bi-funnel me-1"></i> Filtrar / Exportar PDF
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle" id="tablaConcesiones">
                <thead class="table-dark text-center">
                    <tr>
                        <th>NOMBRE DEL CONTRIBUYENTE</th>
                        <th>NÚMERO DE LOTE</th>
                        <th>FECHA DE REFRENDO</th>
                        <th>NOMBRE DE LOS OCCISOS</th>
                        <th>FECHA DE INHUMACIÓN</th>
                        <th>EXHUMACIÓN</th>
                        <th>RE-INHUMACIÓN</th>
                        <th>CONSTRUCCIÓN DE CAPILLA O CRIPTA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($datos as $row)
                        <tr>
                            <td>{{ $row['nombre_contribuyente'] }}</td>
                            <td>{{ $row['numero_lote'] }}</td>
                            <td class="text-center">{{ $row['fecha_refrendo'] }}</td>
                            <td>{{ $row['nombres_occisos'] }}</td>
                            <td class="text-center">{{ $row['fecha_inhumacion'] }}</td>
                            <td class="text-center">{{ $row['exhumacion'] }}</td>
                            <td class="text-center">{{ $row['reinhumacion'] }}</td>
                            <td class="text-center">{{ $row['construccion'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No hay registros con los filtros actuales.
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
<div class="modal fade" id="filtrosConcesionesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-funnel me-2 text-muted"></i>Filtrar reporte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('reportes.concesiones') }}" id="formFiltrosConcesiones">
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
                    <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros('formFiltrosConcesiones', '{{ route('reportes.concesiones') }}')">
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
    const contenido = document.getElementById('tablaConcesiones').outerHTML;
    const titulo    = 'Reporte de Concesiones / Refrendo';

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