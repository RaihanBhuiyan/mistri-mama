@extends('layouts.app')

@section('content')

<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Roles</li>
    </ol>
    <h1 class="page-title">Roles</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" data-toggle="collapse" href="#addNewRole"
            role="button" aria-expanded="false" aria-controls="addNewRole">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New</span>
        </a>
    </div>
</div>


<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Create Role</h3>
    </div>
    <div class="panel-body">
        <div class="collapse @if(count($errors) != 0) show @endif" id="addNewRole">
            <form action="{{route('role.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="type">Role Name</label>
                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="">
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    @endif
                </div>
                <button type="submit"  class="input-group-addon bg-green-600 text-white">Add</button>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                <tr>
                    <td>{{($key+1)}}</td>
                    <td title="ID : {{$role->id}}">{{$role->name}}</td>
                    <td>{{$role->created_at}}</td>
                    <td>
                        <a href="{{ route('role.edit', $role->id) }}"><span class="badge badge-danger"><i class="icon md-edit"></i></span></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@foreach ($errors->toArray() as $error)
@php
toastr()->error($error[0])
@endphp

@endforeach

@endsection