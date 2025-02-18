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
        <li class="breadcrumb-item active">Create Advertisement</li>
    </ol>
    <h1 class="page-title">Create Advertisement</h1>
</div>
<div class="panel">
    <div class="panel-body">
        <form method="POST" action="{{ route('advertisement.store') }}" class="form-horizontal">
            @csrf
            <div class="form-group">
                <label class="control-label">Place Name</label>
                <select class="form-control {{ $errors->has('place_name') ? 'is-invalid' : '' }}" name="place_name">
                    <option value="blog" {{ (old('place_name') == 'blog') ? 'selected' : ''  }}>Blog</option>
                </select>
                @if ($errors->has('place_name'))
                    <div class="invalid-feedback">{{ $errors->first('place_name') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="control-label">Url</label>
                <input placeholder="Url" class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ old('url') }}" name="url" type="text">
                @if ($errors->has('url'))
                    <div class="invalid-feedback">{{ $errors->first('url') }}</div>
                @endif
            </div>
            <!-- Example Default -->
            <div class="example-wrap mb-4">
                <h4 class="example-title mb-1">Upload Advertisement photo here <span class="required">*</span></h4>
                <div class="example m-0" id="picture_upload" @error('image') style="border: 1px solid #f44336;" @enderror>
                    <input type="file" class="dropify" id="image" name="image" data-plugin="dropify" data-default-file=""/>
                </div>
                @if ($errors->has('image'))
                    <div class="text-danger">{{ $errors->first('image') }}</div>
                @endif
            </div>
            <!-- End Example Default -->
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
    $(document).ready(function () {

        $('#checkedAll').on('ifChecked ifUnchecked', function (event) {
            if (event.type == 'ifChecked') {
                $('input.checkSingle').iCheck('check');
            } else {
                $('input.checkSingle').iCheck('uncheck');
            }
        });

        $('input.checkSingle').on('ifChanged', function (event) {
            if ($('input.checkSingle').filter(':checked').length == $('input.checkSingle').length) {
                $('#checkedAll').prop('checked', 'checked');
            } else {
                $('#checkedAll').removeProp('checked');
            }
            $('#checkedAll').iCheck('update');
        });

    });
</script>

<script src="{{asset('theme/ckeditor/ckeditor.js')}}"></script>
<script>
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