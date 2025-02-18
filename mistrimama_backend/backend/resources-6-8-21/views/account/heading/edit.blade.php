@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
@endsection

@section('content')

<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Account</li>
        <li class="breadcrumb-item active">Transaction Headings</li>
    </ol>
    <h1 class="page-title">Transaction Headings</h1>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Transaction Heading</h3>
    </div>
    @if (!empty($heading))
    <div class="panel-body">
        <form method="POST" action="{{route('heading.update', $heading->id)}}">
            @method('put')
            @csrf
            <div class="form-group">
                <label for="type">Transaction Heading Type</label>
                <p><strong>{{ ucfirst($heading->heading_type) }}</strong></p>
            </div>
            <div class="form-group">
                <label for="title">Transaction Heading </label>
                <input type="text" name="title" value="{{ $heading->title }}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">
                @if ($errors->has('title'))
                    <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    @else
    <p class="text-center panel-title">There is no transaction headings found</p>
    @endif
</div>

@if($errors)
    @foreach ($errors->toArray() as $error)
        @php
        toastr()->error($error[0])
        @endphp
    @endforeach
@endif

{{-- <button class="btn btn-primary" id="warning-alert" data-plugin="toastr" data-message="Sabbir Hossain."
    data-container-id="toast-top-right" data-title="Messages" data-close-button="true" data-tap-to-dismiss="false"
    data-icon-class="toast-just-text toast-warning" role="button">Generate</button> --}}
{{-- <a href="{{route('test')}}">Test</a> --}}
<!-- End Panel Full Example -->

@section('scripts')

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>




<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script>
    $(document).ready( function () {
    // $('.table').DataTable();
} );

</script>


@endsection
@endsection