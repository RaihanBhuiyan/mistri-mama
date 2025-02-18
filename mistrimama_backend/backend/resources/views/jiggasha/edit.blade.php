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
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">FAQ</li>
    </ol>
    <h1 class="page-title">FAQ</h1>
    
</div>

<div class="panel" >
    <div class="panel-heading">
        <h3 class="panel-title">Edit FAQ </h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('jiggasha.update',$jiggasha->id)}}">
            @method('patch')
            @csrf
            <div class="form-group">
                <label for="type">Select Type</label>
                <select id="type" class="form-control" name="type">
                    <option value="esp" {{($jiggasha->type == 'esp') ? 'selected' : ''}}>Esp</option>
                    <option value="client" {{($jiggasha->type == 'client') ? 'selected' : ''}}>Client</option>
                    <option value="common" {{($jiggasha->type == 'common') ? 'selected' : ''}}>Common</option>
                </select>
                <div class="invalid-feedback type">&nbsp;</div>
            </div>
            <div class="form-group">
                <label class="form-control-label">Title<span class="required">*</span></label>
                <input type="text" class="form-control" value="{{$jiggasha->title}}" name="title" placeholder="Title" required="">
            </div>
            <div class="form-group">
                <label class="form-control-label">Details<span class="required">*</span></label>
                <textarea class="form-control" name="discription" placeholder="Details" required="">{{$jiggasha->discription}}</textarea>
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