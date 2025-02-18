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
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{ route('feedback.create') }}">
        <i class="icon md-plus" aria-hidden="true"></i>
        <span class="hidden-sm-down">Create New</span>
    </a>
</div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Add New Feedback</h3>
    </div>
    <div class="panel-body">
        <div class="row collapse" id="addNewCategory">
            <div class="col-md-12">
                <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('feedback.index')}}">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-control-label">Categories
                                <span class="required">*</span>
                            </label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Select type</option>
                                @if(!empty($categories))
                                @foreach($categories as $key => $category)
                                <option value="{{ $key }}">{{ $category }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-control-label">Type
                                <span class="required">*</span>
                            </label>
                            <select name="type" id="type" class="form-control">
                                <option value="">Select type</option>
                                <option value="sp">SP</option>
                                <option value="comrade">Comrade</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Feedback Question
                            <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-bus" aria-hidden="true"></i>
                            </span>
                            <input type="text" class="form-control" name="qus" placeholder="Feedback Question" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Feedback Answer
                            <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-bus" aria-hidden="true"></i>
                            </span>
                            <input type="text" class="form-control" name="qus" placeholder="Feedback Answer" required="">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
                </form>
            </div>
        </div>

        <div class="row card card-body">
            <div class="example table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($feedback))
                        @foreach ($feedback as $key => $item)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td>{{$item->category->name}}</td>
                            <td class="text-capitalize">{{$item->type}}</td>
                            <td>{{$item->question}}</td> 
                            <td>@foreach($item->options as $key => $val)
                                    <span class="badge badge-info">{{$val->title}}</span>  
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('feedback.edit', $item->id) }}" class="badge badge-info btn btn-xs">
                                    <i class="icon md-edit"></i>
                                </a>
                                <form method="POST" action="{{route('feedback.destroy',$item->id)}}">
                                    @method('delete')
                                    @csrf
                                    <button class="badge badge-info btn btn-xs btn-danger"
                                    onClick="return confirm('Are you sure you want to delete?')" type="submit"><i
                                    class="icon md-delete"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
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