@extends('administration.layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title mb-0 font-size-18">Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Administración</a></li>
                        <li class="breadcrumb-item active">2020 - 2024</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body mb-4">
                    <h4 class="card-title mb-4">Resumen de lo recaudado en línea</h4>
                    <div class="media">
                        <div class="media-body">
                            <h4>{{ accounting_number_format($amount_successful_payments_final) }}</h4>
                            <p class="mb-0"><span class="badge badge-soft-success mr-2">{{ $percent_successful_payments }} <i class="mdi mdi-arrow-up"></i> </span> Pago exitoso</p>
                        </div>
                    </div>
                    <div class="mt-3 social-source">
                        <div class="media align-items-center social-source-list">
                            <div class="avatar-xs mr-4">
                                <span class="avatar-title rounded-circle bg-secondary">
                                    <i class="mdi mdi-credit-card-settings-outline"></i>
                                </span>
                            </div>
                            <div class="media-body">
                                <p class="mb-1">Pendiente</p>
                                <h5 class="mb-0">{{ accounting_number_format($amount_pending_payments_final) }}</h5>
                            </div>
                            <div>
                                {{ $percent_pending_payments }} <i class="mdi mdi-arrow-up text-success ml-1"></i>
                            </div>
                        </div>

                        <div class="media align-items-center social-source-list">
                            <div class="avatar-xs mr-4">
                                <span class="avatar-title rounded-circle bg-warning">
                                    <i class="mdi mdi-credit-card-clock-outline"></i>
                                </span>
                            </div>
                            <div class="media-body">
                                <p class="mb-1">En tránsito</p>
                                <h5 class="mb-0">{{ accounting_number_format($amount_payments_in_transit_final) }}</h5>
                            </div>
                            <div>
                                {{ $percent_payments_in_transit }} <i class="mdi mdi-arrow-up text-success ml-1"></i>
                            </div>
                        </div>

                        <div class="media align-items-center social-source-list">
                            <div class="avatar-xs mr-4">
                                <span class="avatar-title rounded-circle bg-danger">
                                    <i class="mdi mdi-credit-card-remove-outline"></i>
                                </span>
                            </div>
                            <div class="media-body">
                                <p class="mb-1">Rechazado</p>
                                <h5 class="mb-0">{{ accounting_number_format($amount_declined_payments_final) }}</h5>
                            </div>
                            <div>
                                {{ $percent_declined_payments }} <i class="mdi mdi-arrow-down text-danger ml-1"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="mb-2">Pago exitoso anual</p>
                            <h4 class="mb-0">{{ accounting_number_format($amount_successful_payments_yearly_final) }}</h4>
                            <span class="text-muted">Año {{ now()->year }}</span>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                <div>
                                    {{ percent_format($percent_successful_payments_yearly) }} <i class="mdi mdi-arrow-up text-success ml-1"></i>
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar" role="progressbar" style="width: {{ percent_format($percent_successful_payments_yearly) }}" aria-valuenow="{{ $percent_successful_payments_yearly }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="mb-2">Pago exitoso mensual</p>
                            <h4 class="mb-0">{{ accounting_number_format($amount_successful_payments_monthly_final) }}</h4>
                            <span class="text-muted">Mes {{ now()->formatLocalized('%B') }}</span>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                <div>
                                    {{ percent_format($percent_successful_payments_monthly) }} <i class="mdi mdi-arrow-up text-success ml-1"></i>
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar" role="progressbar" style="width: {{ percent_format($percent_successful_payments_monthly) }}" aria-valuenow="{{ $percent_successful_payments_monthly }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="mb-2">Pago exitoso diario</p>
                            <h4 class="mb-0">{{ accounting_number_format($amount_successful_payments_daily_final) }}</h4>
                            <span class="text-muted">Hoy {{ now()->formatLocalized('%A') }}</span>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                <div>
                                    {{ percent_format($percent_successful_payments_daily) }} <i class="mdi mdi-arrow-up text-success ml-1"></i>
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar" role="progressbar" style="width: {{ percent_format($percent_successful_payments_daily) }}" aria-valuenow="{{ $percent_successful_payments_daily }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="mb-2">Número de pagos exitosos anuales</p>
                            <h4 class="mb-0">{{ $count_successful_payments_yearly_final }}</h4>
                            <span class="text-muted">Año {{ now()->year }}</span>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                <div>
                                    </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar" role="progressbar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="mb-2">Número de pagos exitosos mensuales</p>
                            <h4 class="mb-0">{{ $count_successful_payments_monthly_final }}</h4>
                            <span class="text-muted">Mes {{ now()->formatLocalized('%B') }}</span>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                <div>

                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar" role="progressbar" style="width: {{ percent_format($percent_successful_payments_monthly) }}" aria-valuenow="{{ $percent_successful_payments_monthly }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="mb-2">Número de pagos exitosos diarios</p>
                            <h4 class="mb-0">{{ $count_successful_payments_daily_final }}</h4>
                            <span class="text-muted">Hoy {{ now()->formatLocalized('%A') }}</span>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                <div>

                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar" role="progressbar" style="width: {{ percent_format($percent_successful_payments_daily) }}" aria-valuenow="{{ $percent_successful_payments_daily }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="row">
                @foreach($amount_by_payment_method as $method => $amount)
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="media">
                                    <div class="avatar-sm font-size-20 mr-3">
                                        <span class="avatar-title bg-soft-primary text-primary rounded">
                                            <i class="mdi {{ payment_method_icon($method) }}"></i>
                                        </span>
                                    </div>
                                    <div class="media-body">
                                        <div class="font-size-16 mt-2">{{ $method }}</div>
                                    </div>
                                </div>
                                <h4 class="mt-4">{{ accounting_number_format($amount) }}</h4>
                                <div class="row">
                                    <div class="col-7">
                                        <p class="mb-0"><span class="text-success mr-2"> 0.28% <i class="mdi mdi-arrow-up"></i> </span></p>
                                    </div>
                                    <div class="col-5 align-self-center">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Últimos ciudadanos registrados</h4>
                    <ul class="inbox-wid list-unstyled">
                        @forelse($last_payments as $payment)
                            <li class="inbox-list-item">
                                <a href="#">
                                    <div class="media">
                                        <div class="mr-3 align-self-center">
                                            <img src="https://ui-avatars.com/api/?name={{ $payment->citizen->name }}+{{ $payment->citizen->first_surname }}&background=efefef&color=70B630&bold=true" alt="" class="avatar-sm rounded-circle">
                                        </div>
                                        <div class="media-body overflow-hidden">
                                            <h5 class="font-size-16 mb-1">{{ $payment->citizen->name }} {{ $payment->citizen->first_surname }}</h5>
                                            <p class="text-truncate mb-0">{{ $payment->citizen->email }}</p>
                                        </div>
                                        <div class="font-size-12 ml-2">
                                            {{ $payment->citizen->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="inbox-list-item text-center">
                                No hay información disponible
                            </li>
                        @endforelse
                    </ul>
                    <div class="text-center">
                        <a href="{{ route('administration.payments.index') }}" class="btn btn-primary btn-sm">Ver todos</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Últimas transacciones</h4>
                    <div class="table-responsive">
                        <table class="table table-centered">
                            <thead>
                            <tr>
                                <th scope="col">Solicitado</th>
                                <th scope="col">Trámite</th>
                                <th scope="col">Ciudadano</th>
                                <th scope="col">Importe</th>
                                <th scope="col" colspan="2">Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($last_payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $payment->formality->name }}</td>
                                    <td>{{ $payment->citizen->name }} {{ $payment->citizen->first_surname }}</td>
                                    <td width="100">{{ accounting_number_format($payment->amount) }}</td>
                                    <td>
                                        @switch($payment->code)
                                            @case (null)
                                                <span class="badge badge-soft-secondary font-size-12">Pendiente</span>
                                            @break
                                            @case ('0')
                                                <span class="badge badge-soft-success font-size-12">Pagado</span>
                                            @break
                                            @case ('3')
                                                <span class="badge badge-soft-warning font-size-12">En tránsito</span>
                                            @break
                                            @default
                                                <span class="badge badge-soft-danger font-size-12">Rechazado</span>
                                        @endswitch
                                    </td>
                                  
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No hay información disponible</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('administration.payments.index') }}" class="btn btn-primary btn-sm">Ver todos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
