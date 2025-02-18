@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">

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
        <li class="breadcrumb-item active">On Demand</li>
    </ol>
    <h1 class="page-title">On Demand Users</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{route('ondemand.create')}}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New</span>
        </a>
    </div>
</div>

<div class="panel">

    <div class="panel-body">
        <div class="row card card-body">
            <div class="example table-responsive">
                <table class="table table-stripped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Area</th>
                            <th>Address</th>
                            <th>Balance</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach ($onDemandClients as $client)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$client->name}}</td>
                            <td>{{$client->phone}}</td>
                            <td>{{$client->email}}</td>
                            <td>{{$client->company_name}}</td>
                            <td>{{$client->area}}</td>
                            <td>{{$client->address}}</td>
                            <td class="text-center">{{userBalance($client->user->id)}}</td>
                            <td class="text-center" style="width: 22%;">
                                <a href="{{route('ondemand.edit',$client->id)}}" class="btn btn-xs btn-primary">
                                    <i class="icon md-edit"></i> </a>
                                {{-- <a href="{{route('order.destroy',$client->id)}}" class="btn btn-xs btn-danger"><i
                                    class="icon md-delete"></i></a> --}}
                                <a href="{{route('ondemand.show',$client->id)}}" class="btn btn-xs btn-success"> <i
                                        class="icon md-eye"></i> </a>
                                <form action="{{route('ondemand.destroy',$client->id)}}" class="float-right"
                                    method="POST" style="width:50%">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-xs btn-danger"><i class="icon md-delete"></i></button>
                                </form>
                            </td>
                        </tr>
                        @php
                        $i++;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
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