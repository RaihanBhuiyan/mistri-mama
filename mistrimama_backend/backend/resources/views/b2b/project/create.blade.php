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
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Add New b2b Project </h3>
    </div>
    <div class="panel-body">

        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('client.store')}}">
            @csrf
            <div class="row row-lg">
                <div class="col-xl-6">
                    
                    <div class="form-group ">
                        <label class="col-xl-12 col-md-3 form-control-label">B2B Project Name
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">                                 
                                <input type="text" class="form-control" value="{{ old('project_name') }}" name="b2bid"
                                    placeholder="Project Name" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xl-12 col-md-3 form-control-label">B2B Project Number 
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">                                 
                                <input type="text" class="form-control" value="{{ old('project_number') }}" name="b2bid"
                                    placeholder="Project Number" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xl-12 col-md-3 form-control-label">B2B Register Date
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">                                 
                                <input type="text" class="form-control" value="{{ old('register_date') }}" name="b2bid"
                                    placeholder="Register Date" required="">
                            </div>
                        </div>
                    </div>
                    
                </div> 
                <div class="col-xl-6">
                    <div class="form-group ">
                        <label class="col-xl-12 col-md-3 form-control-label">B2B Project Start
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">                                 
                                <input type="text" class="form-control" value="{{ old('project_start') }}" name="b2bid"
                                    placeholder="Project Start" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xl-12 col-md-3 form-control-label">B2B Deadline
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">                                 
                                <input type="text" class="form-control" value="{{ old('deadline') }}" name="b2bid"
                                    placeholder="Deadline" required="">
                            </div>
                        </div>
                    </div>  
                </div>
                <input type="text" name="type" value="affiliation" hidden>

                </div>

                <div class="form-group form-material col-xl-12 text-left padding-top-m">
                    <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
                </div>
            </div>
        </form>
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