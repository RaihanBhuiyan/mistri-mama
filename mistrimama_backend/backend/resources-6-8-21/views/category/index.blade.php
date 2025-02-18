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
        <li class="breadcrumb-item active">Category</li>
    </ol>
    <h1 class="page-title">Category</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{ route('category.create') }}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Add New Category</h3>
    </div>
    <div class="panel-body">
        <div class="example table-responsive">
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Benefit</th>
                        <th>SP/User Panel Image</th>
                        <th>SP/User Panel Image (Hover)</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($category))
                    @foreach ($category as $key => $category)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td>{{$category->name}}</td>
                        <td>{{$category->slug}}</td>
                        <td>{!! $category->description !!}</td>
                        <td>{!! $category->benifits !!}</td>
                        <td>
                            <img src="{{$category->thumb_url}}" alt="" class="img-responsive" style="width:50px;height:50px">
                        </td>
                        <td>
                            <img src="{{$category->icon_url}}" alt="" class="img-responsive" style="width:50px;height:50px">
                        </td>
                        <td>{{$category->created_at}}</td>
                        <td>
                            <a href="{{ URL('/category/'.$category->id.'/edit') }}"
                                class="badge badge-info btn btn-xs">
                                <i class="icon md-edit"></i>
                            </a>
                            <form method="POST" action="{{route('category.destroy',$category->id)}}">
                                @method('delete')
                                @csrf
                                <button class="badge badge-info btn btn-xs btn-danger" onClick="return confirm('Are you sure you want to delete?')" type="submit"><i class="icon md-delete"></i></button>
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

@section('scripts')

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>

@endsection
@endsection