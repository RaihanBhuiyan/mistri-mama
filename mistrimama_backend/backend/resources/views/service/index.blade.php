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
        <li class="breadcrumb-item active">Service</li>
    </ol>
    <h1 class="page-title">Service</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{ route('service.create') }}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-body">
        <div class="example table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Action</th>
                        <th>Icon</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($service))
                    @foreach ($service as $key => $service)
                    <tr title="{{ $service->position }}">
                        <td>{{($key+1)}}</td>
                        <td>
                            <a href="{{ URL('/service/'.$service->id.'/edit') }}" class="badge badge-info btn btn-xs">Edit</a>
                            <form method="POST" action="{{route('service.destroy',$service->id)}}">
                                @method('delete')
                                @csrf
                                <button class="badge badge-info btn btn-xs btn-danger" onClick="return confirm('Are you sure you want to delete?')" type="submit">Delete</button> 
                            </form>
                            @if($service->is_populer)
                            <a style="margin:2px 0" href="{{ route('toggle-populer', $service->id) }}" class="btn btn-xs btn-danger">Remove Popouler Service</a>
                            @else
                            <a style="margin:2px 0" href="{{ route('toggle-populer', $service->id) }}" class="btn-success btn btn-xs">Make Popouler Service</a>
                            @endif
                        </td>
                        <td> <img src="{{$service->thumb_url}}" alt="icon" style="height: 50px; width:50px"> </td>
                        <td>{{$service->category->name}}</td>
                        <td>{{$service->name}}</td>
                        <td>{{$service->slug}}</td>
                        <td>{!! $service->description !!}</td>
                        <td>{{$service->created_at}}</td>
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
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>

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

<script>
    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a image here or click',
            'replace': 'Drag and drop or click to replace',
            'remove':  'Remove',
            'error':   'Ooops, something wrong happended.'
        }
    });
</script>


@endsection
@endsection