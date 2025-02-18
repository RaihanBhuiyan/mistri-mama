@extends('layouts.app')

@section('styles')
@endsection

@section('content')

<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Service Provider</li>
        <li class="breadcrumb-item active">Transactions</li>
    </ol>
    <h1 class="page-title">Service Provider Transactions</h1>
</div>

<div class="row">
    <div class="col-lg-3">
        <!-- Card -->
        <div class="card card-block p-20">
            <div class="counter counter-md text-left">
                <div class="counter-label text-uppercase mb-5">Last Services</div>
                <div class="counter-number-group mb-10">
                    <span class="counter-number">{{ (!empty($details->last_order)) ? $details->last_order->total_price : 0 }}/-</span>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>

    <div class="col-lg-3">
        <!-- Card -->
        <div class="card card-block p-20">
            <div class="counter counter-md text-left">
                <div class="counter-label text-uppercase mb-5">Last Recharge</div>
                <div class="counter-number-group mb-10">
                    <span class="counter-number">{{ (!empty($details->last_recharge)) ? $details->last_recharge->amount : 0 }}/-</span>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>

    <div class="col-lg-3">
        <!-- Card -->
        <div class="card card-block p-20">
            <div class="counter counter-md text-left">
                <div class="counter-label text-uppercase mb-5">Last Cashout</div>
                <div class="counter-number-group mb-10">
                    <span class="counter-number">{{ (!empty($details->last_withdraw)) ? $details->last_withdraw->amount : 0 }}/-</span>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
    <div class="col-lg-3">
        <!-- Card -->
        <div class="card card-block p-20">
            <div class="counter counter-md text-left">
                <div class="counter-label text-uppercase mb-5">Last Scheme</div>
                <div class="counter-number-group mb-10">
                    <span class="counter-number">{{ (!empty($schemes->amount)) ? $schemes->amount : 0 }}/-</span>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Transactions</h3>
    </div>
    <div class="panel-body">
        @if(count($accounts) > 0)
        <div class="example table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Date</th>
                        <th>Transaction Details</th>
                        <th>TrxID</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $key => $account)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td>{{$account->date}}</td>
                        @if($account->ref == 'order')
                        <td><a target="_blank" href="<?php echo env('APP_URL').'/order/'. $account->details[2]; ?>">{{$account->details[0]}}</a></td>
                        @else
                        <td>{{$account->details}}</td>
                        @endif
                        <td>{{$account->trxno}}</td>
                        <td>{{$account->amount}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center panel-title">There is no transaction found</p>
        @endif
    </div>
</div>

@if($errors)
    @foreach ($errors->toArray() as $error)
        @php
        toastr()->error($error[0])
        @endphp
    @endforeach
@endif



@section('scripts')
<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>

<script>
</script>
@endsection
@endsection