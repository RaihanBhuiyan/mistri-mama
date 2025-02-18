@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<style>
.ck-editor{
    min-width: 100%;
}
.ck-editor__editable {
    min-height: 300px;
}
</style>
@endsection

@section('content')
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Create Post</li>
    </ol>
    <h1 class="page-title">Create Post</h1>
</div>
<div class="panel">
    <div class="panel-body">
        <form method="POST" action="{{ route('blog.store') }}" class="form-horizontal">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label">Title EN</label>
                        <input placeholder="Title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title') }}" name="title" type="text">
                        @if ($errors->has('title'))
                            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Title BN</label>
                        <input placeholder="Title" class="form-control {{ $errors->has('title_bn') ? 'is-invalid' : '' }}" value="{{ old('title_bn') }}" name="title_bn" type="text">
                        @if ($errors->has('title_bn'))
                            <div class="invalid-feedback">{{ $errors->first('title_bn') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Url</label>
                        <input placeholder="Url" class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ old('url') }}" name="url" type="text">
                        @if ($errors->has('url'))
                            <div class="invalid-feedback">{{ $errors->first('url') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Header Content EN</label>
                        <textarea placeholder="Write your content here" id="short_description" class="form-control {{ $errors->has('short_description') ? 'is-invalid' : '' }}" name="short_description" cols="50" rows="10">{{ old('short_description') }}</textarea>
                        @if ($errors->has('short_description'))
                            <div class="invalid-feedback">{{ $errors->first('short_description') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Header Content BN</label>
                        <textarea placeholder="Write your content here" id="short_description_bn" class="form-control {{ $errors->has('short_description_bn') ? 'is-invalid' : '' }}" name="short_description_bn" cols="50" rows="10">{{ old('short_description_bn') }}</textarea>
                        @if ($errors->has('short_description_bn'))
                            <div class="invalid-feedback">{{ $errors->first('short_description_bn') }}</div>
                        @endif
                    </div>
                    <!-- Example Default -->
                    <div class="example-wrap mb-4">
                        <h4 class="example-title mb-1">Upload thumbnail photo here <span class="required">*</span></h4>
                        <div class="example m-0" id="picture_upload" @error('image') style="border: 1px solid #f44336;" @enderror>
                            <input type="file" class="dropify" id="image" name="image" data-plugin="dropify" data-default-file=""/>
                        </div>
                        @if ($errors->has('image'))
                            <div class="text-danger">{{ $errors->first('image') }}</div>
                        @endif
                    </div>
                    <!-- End Example Default -->
                    <div class="form-group">
                        <label class="control-label">Content EN</label>
                        <textarea placeholder="Write your content here" id="content" class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}" name="content" cols="50" rows="10">{{ old('content') }}</textarea>
                        @if ($errors->has('content'))
                            <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Content BN</label>
                        <textarea placeholder="Write your content here" id="content_bn" class="form-control {{ $errors->has('content_bn') ? 'is-invalid' : '' }}" name="content_bn" cols="50" rows="10">{{ old('content_bn') }}</textarea>
                        @if ($errors->has('content_bn'))
                            <div class="invalid-feedback">{{ $errors->first('content_bn') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status">
                            <option value="1" {{ (old('status') == 1) ? 'selected' : ''  }}>Published</option>
                            <option value="0" {{ (old('status') == 0) ? 'selected' : ''  }}>Draft</option>
                        </select>
                        @if ($errors->has('status'))
                            <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Published Date</label>
                        <input placeholder="Published Date" class="form-control {{ $errors->has('published_date') ? 'is-invalid' : '' }}" value="{{ old('published_date') }}" name="published_date" type="date">
                        @if ($errors->has('published_date'))
                            <div class="invalid-feedback">{{ $errors->first('published_date') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border label label-sm label-success">SEO Setting</legend>
                        <div class="form-group">
                            <label class="control-label">Meta Title</label>
                            <input placeholder="Meta Title" id="meta_title" class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}"" value="{{ old('meta_title') }}" name="meta_title" type="text">
                            @if ($errors->has('meta_title'))
                                <div class="invalid-feedback">{{ $errors->first('meta_title') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label">Meta Keyword</label>
                            <input placeholder="Meta Keyword" class="form-control {{ $errors->has('meta_keyword') ? 'is-invalid' : '' }}"" value="{{ old('meta_keyword') }}" name="meta_keyword" type="text">
                            @if ($errors->has('meta_keyword'))
                                <div class="invalid-feedback">{{ $errors->first('meta_keyword') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label">Meta Description</label>
                            <textarea placeholder="Meta Description" id="meta_description" class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}"" name="meta_description" cols="50" rows="10">{{ old('meta_description') }}</textarea>
                            @if ($errors->has('meta_description'))
                                <div class="invalid-feedback">{{ $errors->first('meta_description') }}</div>
                            @endif
                        </div>
                    </fieldset>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

@if($errors)
    @foreach ($errors->toArray() as $error)
        @php
        toastr()->error($error[0])
        @endphp
    @endforeach
@endif

@section('scripts')
<script>
</script>

<script src="{{asset('theme/ckeditor/ckeditor.js')}}"></script>
<script>
ClassicEditor.create( document.querySelector( '#short_description' ), {
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
ClassicEditor.create( document.querySelector( '#short_description_bn' ), {
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
ClassicEditor.create( document.querySelector( '#content_bn' ), {
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
<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>

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