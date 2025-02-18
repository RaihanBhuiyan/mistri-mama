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
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" data-toggle="collapse"
            href="#createTransactionHeadings" role="button" aria-expanded="false" aria-controls="createTransactionHeadings">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create Heading</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Create Transaction Heading</h3>
    </div>
    <div class="panel-body">
        <div class="collapse @if(count($errors) != 0) show @endif" id="createTransactionHeadings">
            <form method="POST" action="{{route('heading.store')}}" style="margin-bottom:15px">
                @csrf
                <div class="form-group">
                    <label for="type">Transaction Heading Type</label>
                    <select name="type" id="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                        <option value="">Select an option</option>
                        <option value="investment" {{ (old('type') == 'investment') ? 'selected' : '' }}>Investment</option>
                        <option value="expenses" {{ (old('type') == 'expenses') ? 'selected' : '' }}>Expenses</option>
                        <option value="revenue" {{ (old('type') == 'revenue') ? 'selected' : '' }}>Revenue</option>
                    </select>
                    @if ($errors->has('type'))
                        <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="title">Transaction Heading </label>
                    <input type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title') }}">
                    @if ($errors->has('title'))
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
        @if (!empty($headings))
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Transaction Heading</th>
                        <th>Transaction Heading Type</th>
                        <th>Type</th>
                        <th class="align-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($headings as $key => $heading)
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td>{{$heading->title}}</td>
                        <td class="text-capitalize">{{$heading->heading_type}}</td>
                        <td class="text-capitalize">{{$heading->type }}</td>
                        <td class="align-center">
                            <a href="{{route('heading.edit', $heading->id)}}" class="btn btn-primary btn-xs">
                                <i class="icon md-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center panel-title">There is no transaction headings found</p>
        @endif
    </div>
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