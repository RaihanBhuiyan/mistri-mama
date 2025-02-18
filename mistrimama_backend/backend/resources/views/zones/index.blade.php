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
        <li class="breadcrumb-item active">Area</li>
    </ol>
    <h1 class="page-title">Area</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" data-toggle="collapse"
            href="#addNewCategory" role="button" aria-expanded="false" aria-controls="addNewCategory">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Add New Area</h3>
    </div>
    <div class="panel-body">
        <div class="row collapse" id="addNewCategory">
            <div class="col-md-12">

                <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('zone.index')}}">
                    @csrf
                    <div class="row row-lg">
                        <div class="col-xl-6 form-horizontal">


                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label">Zone
                                    <span class="required">*</span>
                                </label>
                                <div class="col-xl-12 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon md-bus" aria-hidden="true"></i>
                                        </span>
                                        <select class="form-control" required="" name="cluster_id">
                                            @php
                                            foreach($cluster as $id)
                                            {
                                            @endphp
                                            <option value="{{$id->id}}">{{$id->name}}</option>
                                            @php
                                            }
                                            @endphp
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label">Area
                                    <span class="required">*</span>
                                </label>
                                <div class="col-xl-12 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon md-bus" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" value="{{ old('name') }}" name="name"
                                            placeholder="Name" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label">Code
                                    <span class="required">*</span>
                                </label>
                                <div class="col-xl-12 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon md-bus" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" value="{{ old('code') }}" name="code"
                                            placeholder="Code" required="">
                                    </div>
                                </div>
                            </div>






                        </div>

                        <div class="col-xl-6 form-horizontal">


                        </div>

                        <div class="form-group form-material col-xl-12 text-left padding-top-m">
                            <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row card card-body">
            <div class="example table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Cluster Name</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($zone))
                        @foreach ($zone as $key => $zone)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td>{{$zone->cluster->name}}</td>
                            <td>{{$zone->name}}</td>
                            <td>{{$zone->code}}</td>
                            <td>{{$zone->created_at}}</td>
                            <td>
                                <a href="{{ route('zone.edit', $zone->id) }}" class="badge badge-info btn btn-xs">
                                    <i class="icon md-edit"></i>
                                </a>
                                <form method="POST" action="{{route('zone.destroy',$zone->id)}}">
                                    @method('delete')
                                    @csrf
                                    <button class="badge badge-info btn btn-xs btn-danger" onClick="return confirm('Are you sure you want to delete?')" type="submit">
                                        @if($zone->status==1)
                                        <i class="icon md-play"></i>
                                        @else
                                        <i class="icon md-delete"></i>
                                        @endif
                                        
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
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