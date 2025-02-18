@extends('layouts.app') @section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">

<style>
    .form-horizontal .form-control-label {
        margin-bottom: 0;
        text-align: left;
    }
</style>
@endsection @section('content')
<!-- Panel Full Example -->
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Sliders</li>
    </ol>
    <h1 class="page-title">Sliders</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{route('page.index')}}">
            <i class="icon md-eye" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New Sliders</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Create Slider</h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('slider.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-control-label">Slider Name <span class="required">*</span></label>
                <input type="text" class="form-control" value="{{ old('name') }}" name="name" placeholder="Type slider name" required="">
                @if ($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>
            <div class="form-group">
                <!-- Example Default -->
                <div class="example-wrap m-0">
                    <h4 class="example-title mb-1">Upload Slider Image <span class="required">*</span></h4>
                    <div class="example m-0" id="picture_upload">
                        <input type="file" class="dropify" id="image" name="image" data-plugin="dropify" data-default-file="" />
                    </div>
                    @if ($errors->has('picture_upload'))
                        <div class="text-danger">{{ $errors->first('picture_upload') }}</div>
                    @endif
                </div>
                <!-- End Example Default -->
            </div>
            <button class="btn btn-primary" type="submit">Add Slider</button>
        </form>
    </div>
</div>

@section('scripts')

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>

<script>
$(document).ready(function() {
    var drEvent = $('.dropify').dropify();
    drEvent.on('dropify.afterClear', function(event, element){
        $(this).next('input[type="text"]').remove();
    });
});
</script>

@endsection @endsection