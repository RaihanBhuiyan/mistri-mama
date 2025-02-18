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
<!-- Panel Full Example -->
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Service Bit</li>
    </ol>
    <h1 class="page-title">Service Bit</h1>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Add New Service Bit</h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('servicebit.store')}}">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Service Name
                            <span class="required">*</span>
                        </label>
                        <select class="form-control" required="" name="service_id">
                            @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach($category->services as $service)
                                <option value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Name EN
                            <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" value="{{ old('name') }}" name="name" placeholder="name" required="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Name BN
                            <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" value="{{ old('name_bn') }}" name="name_bn" placeholder="name" required="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">MRP Price
                            <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control" value="{{ old('mrp_price') }}" name="mrp_price" placeholder="MRP Price" required="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Offer Price
                            <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control" value="{{ old('price') }}" name="price" placeholder="Offer Price" required="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Service Provider Price
                            <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control {{ old('service_provider_price') ? 'is-invalid' : '' }}" name="service_provider_price" placeholder="Service Provider Price" required="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Unit
                            <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control" value="{{ old('unit_remarks') }}" name="unit_remarks" placeholder="Unit" required="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Additional Unit
                        <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control" value="{{ old('additional_unit_remarks') }}" name="additional_unit_remarks" placeholder="Additional Unit">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Unit Type
                        <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" value="{{ old('unit_type') }}" name="unit_type" placeholder="Unit Type">
                    </div>
                </div>
            </div>
            <div class="input-group" style="margin-bottom:15px">
                <input type="text" id="tags" class="form-control {{ $errors->has('tags') ? 'is-invalid' : '' }}" value="" name="tags" placeholder="Tags">
                <span class="input-group-btn">
                    <button class="btn btn-primary" id="MakeItTags" type="button">Make IT tags</button>
                </span>
                @if ($errors->has('tags'))
                    <div class="invalid-feedback">{{ $errors->first('tags') }}</div>
                @endif
            </div>
            <div id="previewTags" style="margin-bottom:15px">
            </div>
            <div id="inputsTags" style="display:none">
            </div>
            <div class="form-group">
                <label class="control-label">Brief EN</label>
                <textarea placeholder="Write service brief here" id="brief" class="form-control {{ $errors->has('brief') ? 'is-invalid' : '' }}" name="brief" cols="50" rows="30">{{ old('brief') }}</textarea>
                @if ($errors->has('brief'))
                    <div class="invalid-feedback">{{ $errors->first('brief') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="control-label">Brief BN</label>
                <textarea placeholder="Write service brief here" id="brief_bn" class="form-control {{ $errors->has('brief_bn') ? 'is-invalid' : '' }}" name="brief_bn" cols="50" rows="30">{{ old('brief_bn') }}</textarea>
                @if ($errors->has('brief_bn'))
                    <div class="invalid-feedback">{{ $errors->first('brief_bn') }}</div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
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

<script src="{{asset('theme/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>

<script>
function deleteTag(tag){
    $("#t"+tag+"").remove();
    $("#i"+tag+"").remove();
}
$(document).ready(function() {
    $("#MakeItTags").click(function(){
        var tag =  $("#tags").val();
        if(tag == '')
        {
            $("#tags").focus();
            return false;
        }
        unTag = tag.replace(/ /g, '-');
        $("#previewTags").append('<span style="margin:2px; font-size: 16px; cursor: pointer" id="t'+unTag+'" onclick=deleteTag("'+unTag+'") class="badge badge-primary">'+tag+'<span style="font-size:12px"> &#10060;</span></span>');
        $("#inputsTags").append('<input type="hidden" id="i'+unTag+'" value="'+tag+'" name="tags_values[]">');
        $("#tags").val("");
    });
    ClassicEditor.create( document.querySelector( '#brief' ), {
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
    ClassicEditor.create( document.querySelector( '#brief_bn' ), {
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