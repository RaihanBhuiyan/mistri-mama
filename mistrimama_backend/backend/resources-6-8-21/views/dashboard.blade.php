@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Total Income</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$total_income}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Total Expense</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{ $total_expense }}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Total Order</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number"><?php echo ($total_order); ?></span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Total Recharge</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{ $total_reacharge }}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Today Income</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$today_income}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Yesterdays Income</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$yesterday_income}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Month Income</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{ $this_month_income }}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Previous Month Income</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$previous_month_income}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card p-20 flex-row justify-content-between">
                <div class="counter counter-md text-left">
                    <div class="counter-number-group">
                        <span class="counter-number">{{ $esp }} (<span title="Active" class="text-success">{{$esp_active}}</span>/<span title="In Active" class="text-danger">{{$esp_inactive}}</span>)</span>
                    </div>
                    <a href="{{route('service-provider.type-wise', 'esp')}}">
                        <div class="counter-label text-capitalize font-size-16">Total Number of ESP</div>
                    </a>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card p-20 flex-row justify-content-between">
                <div class="counter counter-md text-left">
                    <div class="counter-number-group">
                        <span class="counter-number">{{ $fsp }} (<span title="Active" class="text-success">{{$fsp_active}}</span>/<span title="In Active" class="text-danger">{{$fsp_inactive}}</span>)</span>
                    </div>
                    <a href="{{route('service-provider.type-wise', 'fsp')}}">
                        <div class="counter-label text-capitalize font-size-16">Total Number of FSP</div>
                    </a>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card p-20 flex-row justify-content-between">
                <div class="counter counter-md text-left">
                    <div class="counter-number-group">
                        <span class="counter-number">{{ $comrades }} (<span title="Active" class="text-success">{{$comrades_active}}</span>/<span title="In Active"  class="text-danger">{{$comrades_inactive}}</span>)</span>
                    </div>
                    <a href="{{route('comrade.index')}}">
                        <div class="counter-label text-capitalize font-size-16">Total Number of Comrade</div>
                    </a>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card p-20 flex-row justify-content-between">
                <div class="counter counter-md text-left">
                    <div class="counter-number-group">
                        <span class="counter-number">{{ $clients }} (<span title="Active" class="text-success">{{$clients_active}}</span>/<span title="In Active" class="text-danger">{{$clients_inactive}}</span>)</span>
                    </div>
                    <a href="{{route('client.index')}}">
                        <div class="counter-label text-capitalize font-size-16">Total Number of Clients</div>
                    </a>
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>
    
    @if(!empty($clients_registrations_year_wise_reports))
    <h3>Total client registration statistics year wise</h3>
    <div class="row">
        @for($i = $current_year; $i > $five_year_ago; $i--)
        @if(array_key_exists($i, $clients_registrations_year_wise_reports))
        <div class="col-lg-3" style="width:20%; max-width: 20%">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">{{ $i }}</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{ $clients_registrations_year_wise_reports[$i] }}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        @endif
        @endfor
    </div>
    @endif

    @if(count($clients_registrations_reports) > 0)
    <h3>Total client registration statistics for {{ $current_year }}</h3>
    <div class="row">
        @foreach($clients_registrations_reports as $clients_registrations_report)
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">{{ $clients_registrations_report->month_name }}</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$clients_registrations_report->total_clients}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        @endforeach
    </div>
    @endif

    <h3>Total Cashout</h3>
    <div class="row">
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">This week Cashout (SP)</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$service_provider_withdraw_week}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Prev week Cashout (SP)</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$service_provider_withdraw_previous_week}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Month Cashout (SP)</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$service_provider_withdraw_this_month}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Previous Month Cashout (SP)</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$service_provider_withdraw_previous_month}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">This week Cashout (Agent)</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$client_cashout_week}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Prev week Cashout (Agent) </div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$client_cashout_previous_week}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Month Cashout (Agent)</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$client_cashout_this_month}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Previous Month Cashout (Agent)</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$client_cashout_previous_month}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>
    
    <h3>Total Recharge</h3>
    <div class="row">
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">This week Recharge (SP)</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$service_provider_recharge_week}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Prev week Recharge (SP)</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$service_provider_recharge_previous_week}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Month Recharge (SP)</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$service_provider_recharge_this_month}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        <div class="col-lg-3">
            <!-- Card -->
            <div class="card card-block p-20">
                <div class="counter counter-md text-left">
                    <div class="counter-label text-uppercase mb-5">Previous Month Recharge (SP)</div>
                    <div class="counter-number-group mb-10">
                        <span class="counter-number">{{$service_provider_recharge_previous_month}}</span>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>
</div>


@endsection