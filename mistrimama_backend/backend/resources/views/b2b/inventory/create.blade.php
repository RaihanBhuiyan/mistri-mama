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
        <h3 class="panel-title">Add New b2b inventory </h3>
    </div>
    <div class="panel-body">

        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('client.store')}}">
            @csrf
            <div class="row row-lg">
                <div class="col-xl-6  ">
                    
                    <div class="form-group ">
                        <label class="col-xl-12 col-md-3 form-control-label">B2B ID
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                 
                                <input type="text" class="form-control" value="{{ old('b2bid') }}" name="b2bid"
                                    placeholder="Jane Doe" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Company Info
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <select class="form-control" name="company">
                                    <option>Company 1</option>
                                    <option>Company 2</option>
                                    <option>Company 3</option>
                                    <option>Company 4</option>
                                </select>
                            </div>
                        </div>
                    </div>  
                    <div class="form-group ">
                        <label class="col-xl-12 col-md-3 form-control-label">Inventory Type
                            <span class="required">*</span>
                        </label> 

                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <select class="form-control" name="inventory_type">
                                    <option>AC</option>
                                    <option>Electrical</option>
                                    <option>Plumbing</option>
                                    <option>IT</option>
                                    <option>CCTV</option>
                                    <option>Generator</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group ">
                        <label class="col-xl-12 col-md-3 form-control-label">Inventory Code
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <input type="text" class="form-control" value="{{ old('inventory_code') }}" name="inventory_code"
                                    placeholder="Inventory Code" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Inventory Name 
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <input type="text" class="form-control" value="{{ old('inventory_name') }}" name="inventory_name"
                                    placeholder="Inventory Name" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Inventory Description 
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <input type="text" class="form-control" value="{{ old('inventory_description') }}" name="inventory_description"
                                    placeholder="Inventory Description" required="">
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-xl-6  "> 

                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Register Date 
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <input type="text" class="form-control" value="{{ old('register_date') }}" name="register_date"
                                    placeholder="Address" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Service History (from B2B)
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <input type="text" class="form-control" value="{{ old('service_history') }}" name="service_history"
                                    placeholder="Service History" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Check Up Observation (From MM Team)
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <input type="text" class="form-control" value="{{ old('check_up_observation') }}" name="check_up_observation" placeholder="Check Up Observation" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Monthly check Up date
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <input type="text" class="form-control" value="{{ old('monthly_check_up_date') }}" name="monthly_check_up_date" placeholder="Monthly check Up date" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group   ">
                        <label class="col-xl-12 col-md-3 form-control-label">Required Service
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <input type="text" class="form-control" value="{{ old('required_service') }}" name="required_service" placeholder="Required Service" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Emmergency Level 
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <input type="text" class="form-control" value="{{ old('emmergency_level') }}" name="emmergency_level"
                                    placeholder="Emmergency Level" required="">
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