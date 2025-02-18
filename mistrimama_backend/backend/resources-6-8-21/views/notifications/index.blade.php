@extends('layouts.app')

@section('content')
<!-- Panel Full Example -->
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Notifications</li>
    </ol>
    <h1 class="page-title">Notifications</h1>
</div>

@if(!empty($notifications))
<div class="list-group">
    @foreach($notifications as $notification)
    <a class="list-group-item mb-1" @if(!empty(json_decode($notification->data)->path)) href="{{ json_decode($notification->data)->path }}" @else href="javascript:void(0)" @endif>
        <div class="media">
            <div class="pr-10">
                <i class="icon md-receipt bg-red-600 white icon-circle" aria-hidden="true"></i>
            </div>
            <div class="media-body">
                <h6 class="media-heading text-truncate">{{ json_decode($notification->data)->title }}</h6>
                <time class="media-meta">at {{ $notification->created_at }}</time>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endif
@endsection