@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">

<style>
    .form-horizontal .form-control-label {
        margin-bottom: 0;
        text-align: left;
    }

</style>
@endsection

@section('content')
<!-- Panel Full Example -->
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Cliams</li>
    </ol>
    <h1 class="page-title">Cliams</h1>
</div>

<div class="panel">
    <div class="example table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Job Number</th>
                    <th>Cliam By</th>
                    <th>Risk Factor</th>
                    <th>Cliam</th>
                    <th>Extra Amount</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($claims))
                @foreach ($claims as $key => $claim)
                <tr>
                    <td>{{($key+1)}}</td>
                    <td><a href="{{route('order.show', $claim->relOrder->id)}}">{{$claim->relOrder->order_no}}</a></td>
                    <td>
                        <p style="margin:0">{{ $claim->relUser->name }}</p>
                        <p style="margin:0">{{ $claim->relUser->phone }}</p>
                    </td>
                    <td>{{$claim->relRiskFactor->title}}</td>
                    <td>{!! $claim->claims_list !!}</td>
                    <td>{{ $claim->comment }}</td>
                    <td>{{$claim->type}}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>


@section('scripts')
<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
@endsection
@endsection