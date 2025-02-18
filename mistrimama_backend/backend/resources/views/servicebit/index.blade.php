@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
{{-- datatable --}}
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-bs4/dataTables.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet"
    href="{{asset('theme/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet"
    href="{{asset('theme/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet"
    href="{{asset('theme/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet"
    href="{{asset('theme/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet"
    href="{{asset('theme/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet"
    href="{{asset('theme/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet"
    href="{{asset('theme/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.minfd53.css?v4.0.1')}}">

<!-- Page -->
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/tables/datatable.minfd53.css?v4.0.1')}}">
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
        <li class="breadcrumb-item active">Service Bit</li>
    </ol>
    <h1 class="page-title">Service Bit</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{ route('servicebit.create') }}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-body">
        <div class="example table-responsive">
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Action</th>
                        <th>Category</th>
                        <th>Service</th>
                        <th>Service Bit</th>
                        <th>MRP Price</th>
                        <th>Price</th>
                        <th>Service Provider Price</th>
                        <th>Unit</th>
                        <th>Additional Unit</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($service_bits))
                    @foreach ($service_bits as $key => $service_bit)
                    <tr class="{{ ($service_bit->checked_features) ? 'bg-warning' : '' }}">
                        <td>{{($key+1)}}</td>
                        <td>
                            <a style="margin:2px 0" href="{{ route('servicebit.edit', $service_bit->id) }}" class="btn-info btn btn-xs">Edit</a>
                            <form method="POST" action="{{route('servicebit.destroy', $service_bit->id)}}">
                                @method('delete')
                                @csrf
                                <button style="margin:2px 0" class="btn btn-xs btn-danger" onClick="return confirm('Are you sure you want to delete?')" type="submit">Delete</button>
                            </form>
                            @if($service_bit->checked_features)
                            <a style="margin:2px 0" href="{{ route('remove_hot_service_bit', $service_bit->id) }}" class="btn btn-xs btn-danger">Remove Hot Service</a>
                            @else
                            <a style="margin:2px 0" href="{{ route('servicebit.edit', $service_bit->id) }}" class="btn-warning btn btn-xs">Make Hot Service</a>
                            @endif
                        </td>
                        <td>{{$service_bit->category->name}}</td>
                        <td>{{$service_bit->service->name}}</td>
                        <td>{{$service_bit->name}}</td>
                        <td>{{$service_bit->mrp_price}}</td>
                        <td>{{$service_bit->price}}</td>
                        <td>{{$service_bit->commission}}</td>
                        <td>{{$service_bit->unit_remarks}}</td>
                        <td>{{$service_bit->additional_unit_remarks}}</td>
                        <td>{{$service_bit->created_at}}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- {{var_dump($errors->toArray())}} --}}
{{-- @if($errors) --}}
@foreach ($errors->toArray() as $error)
@php
toastr()->error($error[0])
@endphp

@endforeach
{{-- @endif --}}

{{-- <button class="btn btn-primary" id="warning-alert" data-plugin="toastr" data-message="Sabbir Hossain."
    data-container-id="toast-top-right" data-title="Messages" data-close-button="true" data-tap-to-dismiss="false"
    data-icon-class="toast-just-text toast-warning" role="button">Generate</button> --}}
{{-- <a href="{{route('test')}}">Test</a> --}}
<!-- End Panel Full Example -->

@section('scripts')

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>

<script src="{{asset('theme/vendor/datatables.net/jquery.dataTablesfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-bs4/dataTables.bootstrap4fd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-fixedheader/dataTables.fixedHeader.minfd53.js?v4.0.1')}}">
</script>
<script src="{{asset('theme/vendor/datatables.net-fixedcolumns/dataTables.fixedColumns.minfd53.js?v4.0.1')}}">
</script>
<script src="{{asset('theme/vendor/datatables.net-rowgroup/dataTables.rowGroup.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-scroller/dataTables.scroller.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-responsive/dataTables.responsive.minfd53.js?v4.0.1')}}">
</script>
<script src="{{asset('theme/vendor/datatables.net-responsive-bs4/responsive.bootstrap4.minfd53.js?v4.0.1')}}">
</script>
<script src="{{asset('theme/vendor/datatables.net-buttons/dataTables.buttons.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-buttons/buttons.html5.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-buttons/buttons.flash.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-buttons/buttons.print.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-buttons/buttons.colVis.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-buttons-bs4/buttons.bootstrap4.minfd53.js?v4.0.1')}}">
</script>
<script>
    $(document).ready( function () {
        $('.table').DataTable();
    });

</script>


@endsection
@endsection