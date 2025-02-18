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
        <li class="breadcrumb-item active">Edit Role</li>
    </ol>
    <h1 class="page-title">Edit Role</h1>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Role</h3>
    </div>
    <div class="panel-body">
        <form action="{{route('role.update', $role->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-control-label" for="name">Role Name</label>
                <input type="text" name="name" value="{{ $role->name }}" id="name" class="form-control" placeholder="Role Name">
                @if($errors->has('name'))
                    <div class="text-danger">{{ $errors->first('name') }}</div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
        </form>
    </div>
</div>

@section('scripts')

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>

@endsection
@endsection