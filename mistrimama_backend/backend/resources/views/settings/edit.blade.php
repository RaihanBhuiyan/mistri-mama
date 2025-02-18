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
        <li class="breadcrumb-item active">Setting</li>
    </ol>
    <h1 class="page-title">Setting</h1>
    
</div>

<div class="panel" >
    <div class="panel-heading">
        <h3 class="panel-title">Edit Setting </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <!--  -->
                <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('setting.update',$setting->id)}}">
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
                                        <input type="text" class="form-control" value="{{$setting->name}}" name="name"
                                            placeholder="name" required="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label">Value
                                    <span class="required">*</span>
                                </label>
                                <div class="col-xl-12 col-md-9">
                                    <div class="input-group" >
                                        <select class="form-control" name="value">
                                            <option value="1" @if($setting->value ==1) selected @endif>Active </option>
                                            <option value="0" @if($setting->value ==0) selected @endif>In-active</option>
                                        </select>
                                        <!-- <input type="number" class="form-control" value="{{$setting->value}}" name="value"
                                            placeholder="Value" required=""> -->
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