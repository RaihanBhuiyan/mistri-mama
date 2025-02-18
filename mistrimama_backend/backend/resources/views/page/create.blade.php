@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
<style>
    .ck-editor{
        min-width: 100%;
    }
    .ck-editor__editable {
        min-height: 300px;
    }
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
        <li class="breadcrumb-item active">Pages</li>
    </ol>
    <h1 class="page-title">Page</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{route('page.index')}}">
            <i class="icon md-eye" aria-hidden="true"></i>
            <span class="hidden-sm-down">View Pages</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">All Pages</h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('page.store')}}">
            @csrf

            <div class="form-group">
                <label class="col-xl-12 col-md-3 form-control-label">Title
                    <span class="required">*</span>
                </label>
                <input type="text" class="form-control" value="{{ old('title') }}" name="title" placeholder="This a page" required="">
            </div>
            <!-- Example Default -->
            <div class="example-wrap m-0">
                <h4 class="example-title mb-1">Page Banner</h4>
                <div class="example m-0" id="picture_upload2">
                    <input type="file" class="dropify" id="media" name="media" data-plugin="dropify" data-default-file="" />
                </div>
            </div>
            <!-- End Example Default -->

            <button type="submit" class="btn btn-primary" id="">Submit</button>
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

<script src="{{asset('theme/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>


<script>
ClassicEditor.create( document.querySelector( '#content' ), {
    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
        ]
    },
})
.catch( error => {
    console.log( error );
});
</script>

<script>
ClassicEditor.create( document.querySelector( '#sub_content' ), {
    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
        ]
    },
})
.catch( error => {
    console.log( error );
});
</script>
<script>
$(document).ready(function() {
    var drEvent = $('.dropify').dropify();
    drEvent.on('dropify.afterClear', function(event, element){
        $(this).next('input[type="text"]').remove();
    });
});
</script>
@endsection
@endsection