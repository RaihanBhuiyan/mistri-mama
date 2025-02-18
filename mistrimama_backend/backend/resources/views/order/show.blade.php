@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
@endsection

@section('content')
<!-- Panel Full Example -->
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Order</li>
        <li class="breadcrumb-item active">Order Details</li>
    </ol>
    <h1 class="page-title">Details</h1>
</div>

<div class="panel" >
    <div class="panel-heading">
        <h3 class="panel-title">Order Details</h3>
    </div>
    @if(!empty($order))
    <table class="table table-bordered">
        <tbody>
            <tr style="background-color:#eae6e6;">
                <td>Order No</td>
                <td>Service Date</td>
                <td>Service Time</td>
                <td>Order Date</td>
            </tr>
            <tr>
                <td>{{$order->order_no}}</td>
                <td>{{$order->date}}</td>
                <td>{{$order->time}}</td>
                <td>{{$order->created_at->format('Y-m-d')}}</td>
            </tr>
            <tr style="background-color:#eae6e6;">
                <td>Service Provider ID</td>
                <td>Service Provider Type</td>
                <td>Service Provider Name</td>
                <td>Service Provider Category</td>
            </tr>
            <tr>
                <td>@if(!empty($order->orderSystem->serviceProvider)) {{ $order->orderSystem->serviceProvider->sp_code}} @else &nbsp; @endif</td>
                <td>@if(!empty($order->orderSystem->serviceProvider)) {{strtoupper($order->orderSystem->serviceProvider->type)}} @else &nbsp; @endif</td>
                <td>@if(!empty($order->orderSystem->serviceProvider)) {{$order->orderSystem->serviceProvider->name}} @else &nbsp; @endif</td>
                <td>
                @if(!empty($order->orderSystem->serviceProvider->service))
                    @foreach ($order->orderSystem->serviceProvider->service as $s)
                    <span class="badge badge-info">{{$s->service->name}}</span>
                    @endforeach
                @else &nbsp; @endif
                </td>
            </tr>
            <tr style="background-color:#eae6e6;">
                <td>Service Provider Zone</td>
                <td>Comrade ID</td>
                <td>Comrade Name</td>
                <td>Status</td>
            </tr>
            <tr>
                <td>
                @if(!empty($order->orderSystem->serviceProvider->zone))
                    @foreach ($order->orderSystem->serviceProvider->zone as $zone)
                    <span class="badge badge-info">{{$zone->zone->name}}</span>
                    @endforeach
                @endif
                </td>
                <td>
                    @if(!empty($order->orderSystem->comrade))
                    {{$order->orderSystem->comrade->comrade_code}}
                    @endif
                </td>
                <td>
                    @if(!empty($order->orderSystem->comrade))
                    {{$order->orderSystem->comrade->name}}
                    @endif
                </td>
                <td>{{$order->status_txt}}</td>
            </tr>
            <tr style="background-color:#eae6e6;">
                <td>User Type</td>
                <td>User Name</td>
                <td>User Phone Number</td>
                <td>User Address</td>
            </tr>
            <tr>
                <td>@if($order->user)  Client @else Guest @endif</td>
                <td>{{$order->name}}</td>
                <td>{{$order->phone}}</td>
                <td>@if($order->user)
                    @if($order->user->client)
                    @if($order->user->client->cluster) 
                    {{ $order->user->client->cluster->name }}, {{ $order->user->client->address }} 
                    @endif
                    @endif
                    @else {{$order->address}} @endif</td>
            </tr>
            <tr style="background-color:#eae6e6;">
                <td colspan="4" class="text-left">Service Zone</td>
            </tr>
            <tr>
                <td colspan="4" class="text-left">{{$order->area}}, {{$order->address}}</td>
            </tr>
            <tr style="background-color:#eae6e6;">
                <td colspan="4" class="text-left">Comments</td>
            </tr>
            <tr>
                <td colspan="4" class="text-left">@if(!empty($order->comments)) {{$order->comments}} @else &nbsp; @endif</td>
            </tr>
            <tr style="background-color:#eae6e6;">
                <td colspan="4" class="text-left">Cancel Reason</td>
            </tr>
            <tr>
                <td colspan="4" class="text-left">@if(!empty($order->cancel_note)) {{$order->cancel_note}} @else &nbsp; @endif</td>
            </tr>
            <tr style="background-color:#eae6e6;">
                <td>Accept Time</td>
                <td>Allowcate Time</td>
                <td>Finish Time</td>
                <td>Cancel Time</td>
            </tr>
            <tr>
                <td>{{$order->accept_time}}</td>
                <td>{{$order->allowcate_time}}</td>
                <td>{{$order->finish_time}}</td>
                <td>{{$order->cancel_time}}</td>
            </tr>
        </tbody>
    </table>
    
    <table class="table table-bordered">
        <thead>
            <tr style="background-color:#eae6e6;">
                <th>Service Category</th>
                <th>Service Name</th>
                <th>Service Bit Name</th>
                <th>Unit</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $item)
            <tr>
                <td>{{$order->category_name}}</td>
                <td>{{$item->service_name}}</td>
                <td>{{$item->service_bit_name}}</td>
                <td>{{$item->quantity}}</td>
                <td>{{$item->price}}</td>
                <td>{{$item->total_price}}</td>
            </tr>
            @endforeach
            <tr style="background-color:#eae6e6;">
                <th colspan="5" class="text-right">Total</th>
                <th>{{$order->total_price}}</th>
            </tr>
            <tr style="background-color:#eae6e6;">
                <th colspan="5" class="text-right">Emergency Hour Charge</th>
                <th>{{$order->emergency_charge}}</th>
            </tr>
            <tr style="background-color:#eae6e6;">
                <th colspan="5" class="text-right">Outside DMC Charge</th>
                <th>{{$order->outside_charge}}</th>
            </tr>
            <tr style="background-color:#eae6e6;">
                <th colspan="5" class="text-right">Order Discount</th>
                <th>{{$order->discount}}</th>
            </tr>
            @if(!empty($order->reduce_type))
            <tr style="background-color:#eae6e6;">
                <th colspan="5" class="text-right">{{ $order->reduce_type }}</th>
                <th>{{$order->reduce_amount}}</th>
            </tr>
            @endif
            <tr style="background-color:#eae6e6;">
                <th colspan="5" class="text-right">Grand Total</th>
                <th>{{ $order->grant_total }}</th>
            </tr>
        </tbody>
    </table>
    @else
    <p class="text-center panel-title">There is no orders found</p>
    @endif
</div>


@foreach ($errors->toArray() as $error)
@php
toastr()->error($error[0])
@endphp

@endforeach




@section('scripts')

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>


@endsection
@endsection