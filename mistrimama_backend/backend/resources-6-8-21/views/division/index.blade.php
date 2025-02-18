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
        <li class="breadcrumb-item active">Division</li>
    </ol>
    <h1 class="page-title">Division</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" data-toggle="collapse" href="#addNewCategory" role="button" aria-expanded="false" aria-controls="addNewCategory">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create Division</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Division</h3>
    </div>
    <div class="panel-body">
        <div class="collapse" id="addNewCategory">
            <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('division.index')}}">
                @csrf
                <div class="form-group">
                    <label class="form-control-label">Division <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-bus" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" value="{{ old('name') }}" name="name" placeholder="Name" required="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Code <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-bus" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" value="{{ old('code') }}" name="code" placeholder="Code" required="">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
            </form>
        </div>
        
        <div class="example table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($division))
                    @foreach ($division as $key => $division)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td>{{$division->name}}</td>
                        <td>{{$division->code}}</td>
                        <td>{{$division->created_at}}</td>
                        <td>
                            <a href="{{ route('division.edit', $division->id) }}" class="btn btn-info btn-xs"><i class="icon md-edit"></i></a>
                            <form method="POST" style="display: inline-block;" action="{{route('division.destroy',$division->id)}}">
                                @method('delete') @csrf
                                <button class="btn btn-danger btn-xs" onClick="return confirm('Are you sure you want to delete?')" type="submit"><i class="icon md-delete"></i></button>
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

{{-- {{var_dump($errors->toArray())}} --}} {{-- @if($errors) --}} @foreach ($errors->toArray() as $error) @php toastr()->error($error[0]) @endphp @endforeach {{-- @endif --}} {{--
<button class="btn btn-primary" id="warning-alert" data-plugin="toastr" data-message="Sabbir Hossain." data-container-id="toast-top-right" data-title="Messages" data-close-button="true" data-tap-to-dismiss="false" data-icon-class="toast-just-text toast-warning" role="button">Generate</button> --}} {{-- <a href="{{route('test')}}">Test</a> --}}
<!-- End Panel Full Example -->

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
            'remove': 'Remove',
            'error': 'Ooops, something wrong happended.'
        }
    });
</script>

@endsection @endsection