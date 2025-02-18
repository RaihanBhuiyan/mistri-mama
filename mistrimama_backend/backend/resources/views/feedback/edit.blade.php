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
        <li class="breadcrumb-item active">feedback</li>
    </ol>
    <h1 class="page-title">feedback</h1>
    
</div>

<div class="panel" >
    <div class="panel-heading">
        <h3 class="panel-title">Edit feedback </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <!--  -->
                <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('feedback.update',$feedback->id)}}">
                    @method('patch')
                    @csrf
                    <div class="form-group">
                        <label class="form-control-label">Categories
                            <span class="required">*</span>
                        </label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Select type</option>
                            @if(!empty($categories))
                            @foreach($categories as $key => $category)
                            <option value="{{ $key }}" {{$feedback->category_id==$key?'selected':''}}>{{ $category }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Type
                            <span class="required">*</span>
                        </label>
                        <select name="type" id="type" class="form-control">
                            <option value="">Select type</option>
                        <option {{$feedback->type=='common'?'selected':''}} value="common">Common</option>
                            <option {{$feedback->type=='sp'?'selected':''}} value="sp">SP</option>
                            <option {{$feedback->type=='user'?'selected':''}} value="user">User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Feedback Question
                            <span class="required">*</span>
                        </label>
                        <div class="input-group"> 
                            <input type="text" class="form-control" value="{{$feedback->question}}" name="question"
                            placeholder="Details" required="">
                        </div>
                    </div>
                    <div class="form-group">
                <label class="form-control-label">Feedback Options
                    <span class="required">*</span>
                </label>

                @if(!empty($feedback->options))
                @foreach($feedback->options as $key => $val)
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-bus" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control" value="{{ $val->title }}" name="option_update[]" placeholder="Feedback Options">
                </div>
                @endforeach
                @endif
                @if(count($feedback->options) < 4)
                @for($i = 0; $i < 4 - count($feedback->options) ; $i++)
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-bus" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control" value="" name="option_new[]" placeholder="Feedback Options">
                </div>
                @endfor
                @endif 
            </div>
                    <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
                </form>
            </div>
        </div>

        
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