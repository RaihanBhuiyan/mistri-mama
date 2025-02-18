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
        <li class="breadcrumb-item active">Division</li>
    </ol>
    <h1 class="page-title">Division</h1>
    
</div>

<div class="panel" >
    <div class="panel-heading">
        <h3 class="panel-title">Edit Division </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <!--  -->
                <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('division.update',$division->id)}}">
                    @method('patch')
                    @csrf

                    <div class="row row-lg">
                        <div class="col-xl-6 form-horizontal">
                            

                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label">Name
                                    <span class="required">*</span>
                                </label>
                                <div class="col-xl-12 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon md-bus" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" value="{{$division->name}}" name="name"
                                            placeholder="name" required="">
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
                                        <input type="number" class="form-control" value="{{$division->code}}" name="code"
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