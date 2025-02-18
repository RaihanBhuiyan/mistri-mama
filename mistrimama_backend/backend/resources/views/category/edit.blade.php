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
</style>
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
        <li class="breadcrumb-item active">Category</li>
    </ol>
    <h1 class="page-title">Category</h1>

</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Category </h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('category.update',$category->id)}}">
            @method('patch')
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Category Name EN</label>
                        <input placeholder="Category Name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $category->name }}" name="name">
                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Category Name BN</label>
                        <input placeholder="Category Name" id="name_bn" class="form-control {{ $errors->has('name_bn') ? 'is-invalid' : '' }}" value="{{ $category->name_bn }}" name="name_bn">
                        @if ($errors->has('name_bn'))
                            <div class="invalid-feedback">{{ $errors->first('name_bn') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="control-label">Description EN</label>
                        <textarea placeholder="Write service description here" id="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" cols="50" rows="10">{{ $category->description }}</textarea>
                        @if ($errors->has('description'))
                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Description BN</label>
                        <textarea placeholder="Write service description here" id="description_bn" class="form-control {{ $errors->has('description_bn') ? 'is-invalid' : '' }}" name="description_bn" cols="50" rows="10">{{ $category->description_bn }}</textarea>
                        @if ($errors->has('description_bn'))
                            <div class="invalid-feedback">{{ $errors->first('description_bn') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Benefit EN</label>
                        <textarea placeholder="Write service benifits here" id="benifits" class="form-control {{ $errors->has('benifits') ? 'is-invalid' : '' }}" name="benifits" cols="50" rows="10">{{ $category->benifits }}</textarea>
                        @if ($errors->has('benifits'))
                            <div class="invalid-feedback">{{ $errors->first('benifits') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Benefit BN</label>
                        <textarea placeholder="Write service benifits here" id="benifits_bn" class="form-control {{ $errors->has('benifits_bn') ? 'is-invalid' : '' }}" name="benifits_bn" cols="50" rows="10">{{ $category->benifits_bn }}</textarea>
                        @if ($errors->has('benifits_bn'))
                            <div class="invalid-feedback">{{ $errors->first('benifits_bn') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Position</label>
                        <input type="text" class="form-control {{ $errors->has('position') ? 'is-invalid' : '' }}" name="position" placeholder="Position" value="{{ $category->position }}">
                        @if ($errors->has('position'))
                            <div class="invalid-feedback">{{ $errors->first('position') }}</div>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Example Default -->
                            <div class="example-wrap mb-4">
                                <h4 class="example-title mb-1">SP/User Panel Image</h4>
                                <div class="example m-0" id="thumbx" @error('thumb') style="border: 1px solid #f44336;" @enderror>
                                    <input type="file" class="dropify" id="thumb" name="thumb" data-plugin="dropify" data-default-file="{{ $category->thumb_url }}"/>
                                </div>
                                @if ($errors->has('thumb'))
                                    <div class="text-danger">{{ $errors->first('thumb') }}</div>
                                @endif
                            </div>
                            <!-- End Example Default -->
                        </div>

                        <div class="col-md-6">
                            <!-- Example Default -->
                            <div class="example-wrap mb-4">
                                <h4 class="example-title mb-1">SP/User Panel Image (Hover)</h4>
                                <div class="example m-0" id="iconx" @error('icon') style="border: 1px solid #f44336;" @enderror>
                                    <input type="file" class="dropify" id="icon" name="icon" data-plugin="dropify" data-default-file="{{ $category->icon_url }}"/>
                                </div>
                                @if ($errors->has('icon'))
                                    <div class="text-danger">{{ $errors->first('icon') }}</div>
                                @endif
                            </div>
                            <!-- End Example Default -->
                        </div>

                        <div class="col-md-6">
                            <!-- Example Default -->
                            <div class="example-wrap mb-4">
                                <h4 class="example-title mb-1">Our Service Image</h4>
                                <div class="example m-0" id="opt_imagex" @error('opt_image') style="border: 1px solid #f44336;" @enderror>
                                    <input type="file" class="dropify" id="opt_image" name="opt_image" data-plugin="dropify" data-default-file="{{ $category->opt_image_url }}"/>
                                </div>
                                @if ($errors->has('opt_image'))
                                    <div class="text-danger">{{ $errors->first('opt_image') }}</div>
                                @endif
                            </div>
                            <!-- End Example Default -->
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
        </form>
    </div>
</div>

@foreach ($errors->toArray() as $error)
@php
toastr()->error($error[0])
@endphp
@endforeach

@section('scripts')

<script src="{{asset('theme/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>

<script src="{{asset('theme/assets/js/Site.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/asscrollable.minfd53.js?v4.0.1')}}"></script>
<script>
$(document).ready(function() {

    ClassicEditor.create( document.querySelector( '#description' ), {
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

    ClassicEditor.create( document.querySelector( '#benifits' ), {
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
    ClassicEditor.create( document.querySelector( '#description_bn' ), {
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

    ClassicEditor.create( document.querySelector( '#benifits_bn' ), {
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


    var drEvent = $('.dropify').dropify();
    drEvent.on('dropify.afterClear', function(event, element){
        $(this).next('input[type="text"]').remove();
    });
});
</script>

@endsection
@endsection