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
        <li class="breadcrumb-item active">Quick Orders</li>
    </ol>
    <h1 class="page-title">Order</h1>

</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Quick Orders</h3>
    </div>
    <div class="panel-body">
        @if(!empty($quickOrders))
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <td>SL</td>
                        <td>Request Services</td>
                        <td>Order Date</td>
                        <td>User Name</td>
                        <td>User Phone Number</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quickOrders as $key =>  $order)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td>{{$order->request_service}}</td>
                        <td>{{$order->created_at}}</td>
                        <td>{{$order->name}}</td>
                        <td>{{$order->phone}}</td>
                        <td>
                            <a href="{{ route('quickorder.edit', $order->id) }}" class="btn btn-xs btn-success">Make Order</a>
                            <form action="{{route('quickorder.destroy', $order->id)}}" method="POST" style="display: inline-block">
                                @csrf
                                @method('delete')
                                <button onClick="return confirm('Are you sure you want to delete?')" class="btn btn-xs btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center panel-title">There is no orders found</p>
        @endif
    </div>
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