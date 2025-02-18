@extends('layouts.app') @section('styles')

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
        <li class="breadcrumb-item active">Offers</li>
    </ol>
    <h1 class="page-title">Offers</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{ route('offer.create') }}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create Offer</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Create Offer</h3>
    </div>
    <div class="panel-body">
        <div class="example table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Offer Title</th>
                        <th>Description</th>
                        <th>Expire Date</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($offers))
                    @foreach ($offers as $key => $offer)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td>
                            <p class="mb-0">{{$offer->title}}</p>
                            <p class="mb-0">Offer for : {{$offer->offers_for}}</p>
                        </td>
                        <td>{{$offer->description}}</td>
                        <td>{{$offer->expire_date}}</td>
                        <td>{{$offer->created_at}}</td>
                        <td>
                            <a title="Edit" href="{{route('offer.edit', $offer->id)}}" class="badge badge-info btn btn-xs btn-info"><i class="icon md-edit"></i></a>



                            <form method="POST" action="{{route('offer.destroy',$offer->id)}}">
                                    @method('delete')
                                    @csrf
                                    <button class="badge badge-info btn btn-xs btn-danger"
                                    onClick="return confirm('Are you sure you want to delete?')" type="submit"><i
                                    class="icon md-delete"></i></button>
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