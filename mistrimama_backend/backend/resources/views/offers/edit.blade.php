@extends('layouts.app') @section('styles')

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
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Offer edit</li>
    </ol>
    <h1 class="page-title">Offer edit</h1>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Offer edit</h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('offer.update',$offer->id)}}">
        @method('patch')
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label class="form-control-label">Title<span class="required">*</span></label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-bus" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control" value="{{$offer->title}}" name="title" placeholder="Offer Title" required="">
                </div>
            </div>
            <div class="form-group col-md-2">
                <label class="form-control-label">Offer For<span class="required">*</span></label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-bus" aria-hidden="true"></i>
                    </span>
                    <select class="form-control" value="{{ old('offers_for') }}" name="offers_for" required="">
                        <option {{($offer->offers_for=='esp'?'selected':'')}} value="esp">ESP</option>
                        <option {{($offer->offers_for=='fsp'?'selected':'')}} value="fsp">FSP</option>
                        <option {{($offer->offers_for=='client'?'selected':'')}} value="client">Client</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-md-4">
                <div class="form-group">
                    <label class="form-control-label">Expire Date<span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-bus" aria-hidden="true"></i>
                        </span>
                        <input type="date" class="form-control" value="{{$offer->expire_date}}" name="expire_date" placeholder="Expire Date" required="">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="form-control-label">Description<span class="required">*</span></label>
            <div class="input-group">
                <textarea type="text" class="form-control" name="description" placeholder="Description" required="">{{$offer->description}}</textarea>
            </div>
        </div>
        <!-- Example Default -->
        <div class="example-wrap mb-4">
            <h4 class="example-title mb-1">Thubanile Image</h4>
            <div class="example m-0" id="offer_imagex" @error('offer_image') style="border: 1px solid #f44336;" @enderror>
                <input type="file" class="dropify" id="offer_image" name="offer_image" data-plugin="dropify" data-default-file="{{ $offer->offer_image_url }}"/>
            </div>
            @if ($errors->has('offer_image'))
                <div class="text-danger">{{ $errors->first('offer_image') }}</div>
            @endif
        </div>
        <!-- End Example Default -->
        <button type="submit" class="btn btn-primary" id="validateButton1">Update</button>
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