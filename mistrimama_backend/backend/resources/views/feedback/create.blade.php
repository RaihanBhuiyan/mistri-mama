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
        <li class="breadcrumb-item active">Feedback</li>
    </ol>
    <h1 class="page-title">Feedback</h1>
    <div class="page-header-actions">
            <!-- <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{ route('feedback.create') }}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New</span>
        </a> -->
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Add New Feedback</h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('feedback.store')}}">
            @csrf
            <div class="form-group">
                <p><strong>Categories *</strong></p>
                @if(!empty($categories))
                @foreach($categories as $key => $category)
                <label class="form-check-label" for="category_id_{{ $key }}">
                    <input type="checkbox" class="form-check-input" id="category_id_{{ $key }}" {{ (!empty(old('category_id')) && sizeof(old('category_id')) > 0) ? (in_array($key, old('category_id'))) ? 'checked' : ''  : '' }} value="{{ $key }}" name="category_id[]">
                    {{ $category }}&nbsp;&nbsp;
                </label>
                @endforeach
                @endif
                @if ($errors->has('category_id'))
                    <div class="text-danger">{{ $errors->first('category_id') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="form-control-label">Type
                    <span class="required">*</span>
                </label>
                <select name="type" id="type" class="form-control">
                    <option value="">Select type</option>
                    <option value="common">Common</option>
                    <option value="sp">SP</option>
                    <option value="user">User</option>
                </select>
                @if ($errors->has('type'))
                    <div class="text-danger">{{ $errors->first('type') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="form-control-label">Feedback Question
                    <span class="required">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-bus" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control" name="question" placeholder="Feedback Question">
                </div>
                @if ($errors->has('question'))
                    <div class="text-danger">{{ $errors->first('question') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="form-control-label">Feedback Options
                    <span class="required">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-bus" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control" name="option[]" placeholder="Feedback Options">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-bus" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control" name="option[]" placeholder="Feedback Options">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-bus" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control" name="option[]" placeholder="Feedback Options">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-bus" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control" name="option[]" placeholder="Feedback Options">
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