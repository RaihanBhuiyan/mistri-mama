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
        <li class="breadcrumb-item active">Risk Factor</li>
    </ol>
    <h1 class="page-title">Risk Factor</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{ route('risk-factors.create') }}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="example table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Particulars</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($risk_factors))
                @foreach ($risk_factors as $key => $riskfactor)
                <tr>
                    <td>{{($key+1)}}</td>
                    <td>{{$riskfactor->title}}</td>
                    <td>{{$riskfactor->type}}</td>
                    <td>{!! $riskfactor->particulars !!}</td>
                    <td>
                        <a href="{{ route('risk-factors.edit', $riskfactor->id) }}" class="btn btn-xs btn-info"><i class="icon md-edit"></i></a>
                        <form action="{{route('risk-factors.destroy',$riskfactor->id)}}" method="POST">
                            @csrf @method('delete')
                            <button onClick="return confirm('Are you sure you want to delete?')" class="btn btn-xs btn-danger"><i class="icon md-delete"></i></button>
                        </form>
                    </td>
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