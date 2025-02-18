@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">

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
        <li class="breadcrumb-item active">Risk Factor</li>
    </ol>
    <h1 class="page-title">Risk Factor</h1>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Risk Factor</h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('risk-factors.update', $risk_factor->id)}}">
            @method('patch')
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label class="form-control-label" for="type">Select Type</label>
                    <select id="type" class="form-control" name="type">
                        <option value="client" {{ ($risk_factor->type == 'client') ? 'selected' : '' }}>Client</option>
                        <option value="service_provider" {{ ($risk_factor->type == 'service_provider') ? 'selected' : '' }}>Service Provider</option>
                    </select>
                    @if($errors->has('type'))
                        <div class="text-danger">{{ $errors->first('type') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-8">
                    <label class="form-control-label">Title<span class="required">*</span></label>
                    <input type="text" class="form-control" value="{{ $risk_factor->title }}" name="title" placeholder="Title">
                    @if($errors->has('title'))
                        <div class="text-danger">{{ $errors->first('title') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="form-control-label">Particulars<span class="required">*</span></label>
                <textarea class="form-control" name="particulars" id="particulars" placeholder="Relavent Questionnaire to Clam" rows="5">{{ $risk_factor->particular_list }}</textarea>
                @if($errors->has('particulars'))
                    <div class="text-danger">{{ $errors->first('particulars') }}</div>
                @endif
            </div>
            
            <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
        </form>
    </div>
</div>

@section('scripts')

<script src="{{asset('theme/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

<script>

$(document).ready( function () {
    ClassicEditor.create( document.querySelector( '#particulars' ), {
        toolbar: [ 'bulletedList' ],
    })
    .catch( error => {
        console.log( error );
    });
});
</script>

@endsection
@endsection