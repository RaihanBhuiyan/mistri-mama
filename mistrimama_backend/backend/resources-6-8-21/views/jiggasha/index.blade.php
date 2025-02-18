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
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" data-toggle="collapse"
        href="#addNewCategory" role="button" aria-expanded="false" aria-controls="addNewCategory">
        <i class="icon md-plus" aria-hidden="true"></i>
        <span class="hidden-sm-down">Create New</span>
    </a>
</div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Add New FAQ</h3>
    </div>
    <div class="panel-body">
        <div class="row collapse" id="addNewCategory">
            <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('jiggasha.index')}}">
                @csrf
                <div class="form-group">
                    <label for="type">Select Type</label>
                    <select id="type" class="form-control" name="type">
                        <option value="esp">Esp</option>
                        <option value="client">Client</option>
                        <option value="common">Common</option>
                    </select>
                    <div class="invalid-feedback type">&nbsp;</div>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Title<span class="required">*</span></label>
                    <input type="text" class="form-control" value="{{ old('title') }}" name="title" placeholder="Title" required="">
                </div>
                <div class="form-group">
                    <label class="form-control-label">Details<span class="required">*</span></label>
                    <textarea class="form-control" name="discription" placeholder="Details" required="">{{ old('discription') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
            </form>
        </div>

        <div class="row card card-body">
            <div class="example table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($jiggashas))
                        @foreach ($jiggashas as $key => $jiggasha)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td>{{$jiggasha->type}}</td>
                            <td>{{$jiggasha->title}}</td>
                            <td>{{$jiggasha->details}}</td>
                            <td>{{$jiggasha->created_at}}</td>
                            <td>
                                <a href="{{ URL('/jiggasha/'.$jiggasha->id.'/edit') }}" class="badge badge-info btn btn-xs">
                                    <i class="icon md-edit"></i>
                                </a>
                                <form method="POST" action="{{route('jiggasha.destroy',$jiggasha->id)}}">
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