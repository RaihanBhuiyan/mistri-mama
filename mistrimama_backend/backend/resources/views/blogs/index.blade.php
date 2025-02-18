@extends('layouts.app')

@section('styles')
@endsection

@section('content')

<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Blogs</li>
    </ol>
    <h1 class="page-title">Blogs</h1>
    <div class="page-header-actions">
        <a href="{{ route('blog.create') }}" class="btn btn-sm btn-primary btn-round waves-effect waves-classic">Create Post</a>
    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Manage Post</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            @if(!empty($posts))
            <table class="table">
                <thead class="flip-content">
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Total Comment</th>
                        <th>Total Like</th>
                        <th>Status</th>
                        <th class="align-center">Action </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                    <tr>
                        <td>
                            <img width="50px" src="{{ $post->thumb }}" alt="">
                        </td>
                        <td><a href="{{ $post->url }}" title="{{ $post->url }}">{{ $post->title }}</a></td>
                        <td><i class="fa fa-comments-o">&nbsp;</i>{{ $post->total_comments }}</td>
                        <td><i class="fa fa-thumbs-up">&nbsp;</i>{{ $post->total_like }}</td>
                        <td>
                            @if($post->status)
                            <span class='label label-sm label-success'> Published </span>
                            @else
                            <span class='label label-sm label-danger'> Draft </span>
                            @endif
                        </td>
                        <td class="align-center">
                            <a class="btn btn-success btn-xs" href="{{ route('blog.show', $post->id) }}">Details</a>
                            <a class="btn btn-info btn-xs" href="{{ route('blog.edit', $post->id) }}"><i class="icon md-edit"></i></a>

                            <form action="{{route('blog.destroy', $post->id)}}" method="POST">
                                @csrf
                                @method('delete')
                                <button onClick="return confirm('Are you sure you want to delete?')" class="btn btn-xs btn-danger"><i class="icon md-delete"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@section('scripts')
@endsection
@endsection