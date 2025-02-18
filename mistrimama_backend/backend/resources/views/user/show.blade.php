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
        <h3 class="panel-title">Profile</h3>
    </div>
    <div class="panel-body">

            <div class="row row-lg">
                <div class="col-xl-6 form-horizontal">
                    <div class="form-group row form-material">
                        <label class="col-xl-12 col-md-3 form-control-label">Full Name
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon md-accounts-add" aria-hidden="true"></i>
                                </span>
                                <p>{{$user->name}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-material">
                        <label class="col-xl-12 col-md-3 form-control-label">Phone Number
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon md-phone" aria-hidden="true"></i>
                                </span>
                                <p>{{$user->phone}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-material">
                        <label class="col-xl-12 col-md-3 form-control-label">Email
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon md-email" aria-hidden="true"></i>
                                </span>
                                <p>{{$user->email}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row form-material">
                        <label class="col-xl-12 col-md-3 form-control-label">Address
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon md-map" aria-hidden="true"></i>
                                </span>
                                <p>{{$admin->address}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row form-material">
                        <label class="col-xl-12 col-md-3 form-control-label">Role
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon md-case-check" aria-hidden="true"></i>
                                </span>
                                <p>{{$role->name}}</p>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="col-xl-6 form-horizontal">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Example Default -->
                            <div class="example-wrap m-0">
                                <h4 class="example-title mb-1">Display Picture</h4>
                                <div class="example m-0" id="picture_upload">
                                    <img width="200px" src="{{$admin->photo_url}}">
                                </div>
                            </div>
                            <!-- End Example Default -->
                        </div>
                    </div>
                   
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