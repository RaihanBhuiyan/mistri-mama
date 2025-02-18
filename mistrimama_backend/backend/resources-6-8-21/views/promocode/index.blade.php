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
        <li class="breadcrumb-item active">Promocode</li>
    </ol>
    <h1 class="page-title">Promocode</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{ route('promocode.create') }}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="example table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Promocode</th>
                    <th>Cash</th>
                    <th>Percent</th>
                    <th>Up To</th>
                    <th>Uses Count</th>
                    <th>Details</th>
                    <th>Validity Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($promocode))
                @foreach ($promocode as $key => $promocode)


                <tr>
                    <td>{{($key+1)}}</td>
                    <td>{{$promocode->promocode}}</td>
                    <td>{{$promocode->cash}}</td>
                    <td>{{$promocode->percent}}</td>
                    <td>{{$promocode->up_to}}</td>
                    <td>{{$promocode->uses_count}}</td>
                    <td>{{$promocode->details}}</td>
                    <td>{{$promocode->validity_date}}</td>
                    <td>
                        <a href="{{ asset('/promocode/'.$promocode->id.'/edit') }}"
                            class="badge badge-info btn btn-xs">
                            <i class="icon md-edit"></i>
                        </a>
                        <!-- 
                            It doesn't have to be deleted, but it can expire.
                            <form method="POST" action="{{route('promocode.destroy',$promocode->id)}}">
                            @method('delete')
                            @csrf
                            <button class="badge badge-info btn btn-sm btn-danger"
                                onClick="return confirm('Are you sure you want to delete?')" type="submit"><i
                                    class="icon md-delete"></i></button>
                            </form>
                         -->
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>


@section('scripts')
<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
@endsection
@endsection