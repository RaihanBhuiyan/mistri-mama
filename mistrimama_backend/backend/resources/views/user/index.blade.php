@extends('layouts.app')

@section('content')

<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Users</li>
    </ol>
    <h1 class="page-title">Users</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{ route('users.create') }}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-body">
        <div class="example table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>User Role</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($admins))
                    @foreach ($admins as $key => $admin)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td>{{$admin->name}}</td>
                        <td>{{$admin->phone}}</td>
                        <td>{{$admin->email}}</td>
                        <td>{{$admin->address}}</td>
                        <td class="text-capitalize">{{$admin->user->getRoleNames()->first()}}</td>
                        <td>{{$admin->created_at}}</td>
                        <td>
                            <a href="{{ route('users.edit', $admin->user_id) }}"
                                class="badge badge-danger btn btn-xs">
                                <i class="icon md-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach ($errors->toArray() as $error)
@php
toastr()->error($error[0])
@endphp

@endforeach

@endsection