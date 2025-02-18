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
        <h3 class="panel-title">On Demand</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2 offset-md-5 justify-content-center">
                <img src="{{$onDemandClients->company_logo}}" alt="" style="height: 100px;width: 100%;">
                <h2 class="text-center">{{$onDemandClients->company_name}}</h2>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="border-bottom">Authorise Persone Details:</h6>
                    </div>
                    <div class="col-md-6 float-right">
                        <strong>Out Standing Balance:</strong> {{userBalance($onDemandClients->user_id)}}/-
                    </div>
                </div>

                <ul class="list-group">
                    <li class="list-group-item"><strong>Name:</strong> {{$onDemandClients->name}}</li>
                    <li class="list-group-item"><strong>Phone:</strong> {{$onDemandClients->phone}}</li>
                    <li class="list-group-item"><strong>Email:</strong> {{$onDemandClients->email}}</li>
                    <li class="list-group-item"><strong>Address:</strong> {{$onDemandClients->address}}</li>
                    <li class="list-group-item"><strong>Area:</strong> {{$onDemandClients->area}}</li>
                    {{-- <li class="list-group-item"><strong>Area:</strong> {{$onDemandClients->area}}</li> --}}
                </ul>
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