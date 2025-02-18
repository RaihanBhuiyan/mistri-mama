@extends('layouts.app')

@section('styles')
@endsection

@section('content')
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Post Details</li>
    </ol>
    <h1 class="page-title">Post Details</h1>
</div>
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Post Details</h3>
    </div>
    <table class="table">
        <tbody>
            <tr>
                <th>Title </th>
                <td>{{ $post->title }}</td>
            </tr>
            <tr>
                <th>URL </th>
                <td>{{ $post->url }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($post->status)
                    <span class='label label-sm label-success'> Active </span>
                    @else
                    <span class='label label-sm label-danger'> Inactive </span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{!! $post->content !!}</td>
            </tr>
            <tr>
                <th>Image </th>
                <td>
                    <img width="80px" src="{{ $post->thumb }}" alt="">
                </td>
            </tr>
            <tr>
                <th>Meta Title </th>
                <td>{{ $post->meta_title }}</td>
            </tr>
            <tr>
                <th>Meta Description</th>
                <td>{{ $post->meta_description }}</td>
            </tr>
            <tr>
                <th>Meta Keyword</th>
                <td><?= $post->meta_keyword ?></td>
            </tr>
            <tr>
                <th>Create On</th>
                <td>{{ $post->created_at }}</td>
            </tr>
            <tr>
                <th>Update On</th>
                <td>{{ $post->updated_at }}</td>
            </tr>
        </tbody>
    </table>
</div>


<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Comments ({{ $post->relComments->count() }})</h3>
    </div>
    <form method="POST" action="javaScript:;" class="form-horizontal">
    @csrf
    <table class="table">
        @if(!empty($post->relComments))
        @foreach($post->relComments as $comment)
        <tr>
            <td style="width:56px">
                <img src="{{ $comment->author_thumb }}" alt="" style="width: 40px; height: 40px; border-radius: 100%;"/>
            </td>
            <td style="width:56px"></td>
            <td>
                <p style="margin:0">{{ $comment->name }} <strong>({{ $comment->phone }})</strong></p>
                <p style="margin:0">{{ $comment->message }}</p>
            </td>
            <td>
                <a href="{{ route('comment.removed', $comment->id) }}" class="btn btn-sm btn-danger">Remove</a>
            </td>
        </tr>
        @if(!empty($comment->relReply))
        @foreach($comment->relReply as $reply)
        <tr>
            <td style="width:56px"></td>
            <td style="width:56px">
                <img src="{{ $reply->author_thumb }}" alt="" style="width: 40px; height: 40px; border-radius: 100%;"/>
            </td>
            <td>
                <p style="margin:0">{{ $reply->name }} <strong>({{ $reply->phone }})</strong></p>
                <p style="margin:0">{{ $reply->message }}</p>
            </td>
            <td>
                <a href="{{ route('comment.removed', $reply->id) }}" class="btn btn-sm btn-danger">Remove</a>
            </td>
        </tr>
        @endforeach
        @endif
        <tr id="replay_view_{{ $post->id }}{{ $comment->id }}">
            <td style="width:56px"></td>
            <td style="width:56px">
                <img src="{{ $author->admin->photo_url }}" alt="" style="width: 40px; height: 40px; border-radius: 100%;"/>
            </td>
            <td colspan="2" style="text-align:left">
                <p style="margin:0">{{ $author->name }} <strong>({{ $author->phone }})</strong></p>
                <div class="input-group">
                    <input placeholder="Write down your replay" class="form-control {{ $errors->has('replay') ? 'is-invalid' : '' }}" value="{{ old('replay') }}" name="replay_{{ $post->id }}{{ $comment->id }}" type="text">
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="button" onClick="functionReplay({{ $post->id }},{{ $comment->id }})">Replay</button>
                    </span>
                </div>
            </td>
        </tr>

        @endforeach
        @endif
    </table>
    </form>
</div>


@if($errors)
    @foreach ($errors->toArray() as $error)
        @php
        toastr()->error($error[0])
        @endphp
    @endforeach
@endif

@section('scripts')

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>

<script>
function functionReplay(post_id, comment_id){
    var comment = $('input[name="replay_'+post_id+''+comment_id+'"]').val();
    $.ajax({
        url: "{{ url('leave_comment') }}/" + post_id,
        type: 'post',
        data: {
            "_token": "{{ csrf_token() }}",
            "comment_id": comment_id,
            "name": "{{ $author->name }}",
            "phone": "{{ $author->phone }}",
            "comments": comment
        },
        dataType: 'json',
        success: function(response) {
            console.log(response);
            var tr = '<tr>';
                tr += '<td style="width:56px"></td>';
                tr += '<td style="width:56px">';
                tr += '<img src="'+response.comment.author_thumb+'" alt="" style="width: 40px; height: 40px; border-radius: 100%;"/>';
                tr += '</td>';
                tr += '<td>';
                tr += '<p style="margin:0">'+response.comment.name+' <strong>('+response.comment.phone+')</strong></p>';
                tr += '<p style="margin:0">'+response.comment.message+'</p>';
                tr += '</td>';
                tr += '<td>';
                tr += '<a href="" class="btn btn-sm btn-danger">Remove</a>';
                tr += '</td>';
                tr += '</tr>';
            $('#replay_view_'+post_id+''+comment_id+'').before(tr);
        }
    });
}
$(document).ready( function () {
});
</script>
@endsection
@endsection