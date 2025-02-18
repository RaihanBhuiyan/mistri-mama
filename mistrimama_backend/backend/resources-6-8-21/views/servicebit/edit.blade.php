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
        <h3 class="panel-title">Edit Service Bit </h3>
    </div>
    <div class="panel-body">
        
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('servicebit.update',$service_bit->id)}}">
            @method('patch')
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Service Name
                            <span class="required">*</span>
                        </label>
                        <select class="form-control {{ $errors->has('service_id') ? 'is-invalid' : '' }}" required="" name="service_id">
                            @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach($category->services as $service)
                                <option value="{{$service->id}}" {{($service_bit->service_id == $service->id) ? 'selected' : ''}}>{{$service->name}}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        @if ($errors->has('service_id'))
                            <div class="invalid-feedback">{{ $errors->first('service_id') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Name EN
                            <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $service_bit->name}}" name="name" placeholder="name" required="">
                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Name BN
                            <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control {{ $errors->has('name_bn') ? 'is-invalid' : '' }}" value="{{ $service_bit->name_bn}}" name="name_bn" placeholder="name" required="">
                        @if ($errors->has('name_bn'))
                            <div class="invalid-feedback">{{ $errors->first('name_bn') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">MRP Price
                            <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control {{ $errors->has('mrp_price') ? 'is-invalid' : '' }}" value="{{ $service_bit->mrp_price }}" name="mrp_price" placeholder="MRP Price" required="">
                        @if ($errors->has('mrp_price'))
                            <div class="invalid-feedback">{{ $errors->first('mrp_price') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Offer Price
                            <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" value="{{ $service_bit->price }}" name="price" placeholder="Offer Price" required="">
                        @if ($errors->has('price'))
                            <div class="invalid-feedback">{{ $errors->first('price') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Service Provider Price
                            <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control {{ $errors->has('service_provider_price') ? 'is-invalid' : '' }}" value="{{ $service_bit->commission }}" name="service_provider_price" placeholder="Service Provider Price" required="">
                        @if ($errors->has('service_provider_price'))
                            <div class="invalid-feedback">{{ $errors->first('service_provider_price') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Unit
                            <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control {{ $errors->has('unit_remarks') ? 'is-invalid' : '' }}" value="{{ $service_bit->unit_remarks }}" name="unit_remarks" placeholder="Unit" required="">
                        @if ($errors->has('unit_remarks'))
                            <div class="invalid-feedback">{{ $errors->first('unit_remarks') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Additional Unit
                        <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control {{ $errors->has('additional_unit_remarks') ? 'is-invalid' : '' }}" value="{{ $service_bit->additional_unit_remarks }}" name="additional_unit_remarks" placeholder="Additional Unit">
                        @if ($errors->has('additional_unit_remarks'))
                            <div class="invalid-feedback">{{ $errors->first('additional_unit_remarks') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Unit Type
                        <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control {{ $errors->has('unit_type') ? 'is-invalid' : '' }}" value="{{ $service_bit->unit_type }}" name="unit_type" placeholder="Unit Type">
                        @if ($errors->has('unit_type'))
                            <div class="invalid-feedback">{{ $errors->first('unit_type') }}</div>
                        @endif
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
            @if(!empty($service_bit->tags_values))
            @foreach($service_bit->tags_values as $tag)
            @php
            $untag = str_replace(" ", "-", $tag);
            @endphp
            <span style="margin:2px; font-size: 16px; cursor: pointer" id="t{{ $untag }}" onclick="deleteTag('{{ $untag }}')" class="badge badge-primary">{{ $tag }}<span style="font-size:12px"> &#10060;</span></span>
            @endforeach
            @endif
            </div>
            <div id="inputsTags" style="display:none">
            @if(!empty($service_bit->tags_values))
            @foreach($service_bit->tags_values as $tag)
            @php
            $untag = str_replace(" ", "-", $tag);
            @endphp
            <input type="hidden" id="i{{ $untag }}" value="{{ $tag }}" name="tags_values[]">
            @endforeach
            @endif
            </div>
            <div class="form-group">
                <label class="control-label">Brief EN</label>
                <textarea placeholder="Write service brief here" id="brief" class="form-control {{ $errors->has('brief') ? 'is-invalid' : '' }}" name="brief" cols="50" rows="30">{{ $service_bit->brief }}</textarea>
                @if ($errors->has('brief'))
                    <div class="invalid-feedback">{{ $errors->first('brief') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="control-label">Brief BN</label>
                <textarea placeholder="Write service brief here" id="brief_bn" class="form-control {{ $errors->has('brief_bn') ? 'is-invalid' : '' }}" name="brief_bn" cols="50" rows="30">{{ $service_bit->brief_bn }}</textarea>
                @if ($errors->has('brief_bn'))
                    <div class="invalid-feedback">{{ $errors->first('brief_bn') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="form-control-label">Make It Features</label>
                <select class="form-control {{ $errors->has('is_features') ? 'is-invalid' : '' }}" required="" name="is_features">
                    <option value="false" {{((old('is_features') == 'false') || ($service_bit->checked_features == false)) ? 'selected' : '' }}>No</option>
                    <option value="true" {{((old('is_features') == 'true') || ($service_bit->checked_features == true)) ? 'selected' : '' }}>Yes</option>
                </select>
                @if ($errors->has('is_features'))
                    <div class="invalid-feedback">{{ $errors->first('is_features') }}</div>
                @endif
            </div>
            <!-- Example Default -->
            <div class="example-wrap mb-4">
                <h4 class="example-title mb-1">Features Image</h4>
                <div class="example m-0" id="features_imagex" @error('features_image') style="border: 1px solid #f44336;" @enderror>
                    <input type="file" class="dropify" id="features_image" name="features_image" data-plugin="dropify" data-default-file="{{ $service_bit->features_thumb }}"  data-allowed-file-extensions="jpg jpeg"/>
                </div>
                @if ($errors->has('features_image'))
                    <div class="text-danger">{{ $errors->first('features_image') }}</div>
                @endif
            </div>
            <!-- End Example Default -->
            <button type="submit" class="btn btn-primary" id="">Submit</button>
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
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>

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