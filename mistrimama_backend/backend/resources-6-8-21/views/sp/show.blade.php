@extends('layouts.app') @section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
<!-- Plugins For This Page -->

@endsection
@section('content')
<!-- Panel Full Example -->
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Service Provider</li>
    </ol>
    <h1 class="page-title">Service Provider Details</h1>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{$serviceProvider->name}} [ SP Code : {{$serviceProvider->sp_code}} ]</h3>
    </div>
    <table class="table">
        <tr>
            <td>Profile Photo</td>
            <td>
                <img data-toggle="modal" data-target="#serviceProviderPPModal" style="width:80px; height:80px; border-radius: 100px;" src="{{asset('upload/sp/'.$serviceProvider->photo)}}" alt="">
            </td>
        </tr>
        <tr>
            <td>Service Provider ID</td>
            <td>{{$serviceProvider->sp_code}}</td>
        </tr>
        <tr>
            <td>Service Provider Joining Date</td>
            <td>{{ $serviceProvider->created_at->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td>Service Provider Type</td>
            <td>{{strtoupper($serviceProvider->type)}}</td>
        </tr>
        <tr>
            <td>Service Provider Name</td>
            <td>{{$serviceProvider->name}}</td>
        </tr>
        <tr>
            <td>Service Provider Category</td>
            <td>
                @if(!empty($serviceProvider->service))
                    @foreach ($serviceProvider->service as $s)
                    <span class="badge badge-info">{{$s->service->name}}</span>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td>Service Provider Zone</td>
            <td>
                <ul class="p-0 m-0 ">
                @foreach ($serviceProvider->cluster as $cluster)
                <li style="list-style:none">{{$cluster->cluster->name}}</li>
                @endforeach
                <ul>
            </td>
        </tr>
        <tr>
            <td>Service Provider Cluster</td>
            <td>
                <ul class="p-0 m-0 ">
                @foreach ($serviceProvider->zone as $zone)
                <li style="list-style:none">{{$zone->zone->name}}</li>
                @endforeach
                <ul>
            </td>
        </tr>
        <tr>
            <td>Address</td>
            <td>{{$serviceProvider->address}}</td>
        </tr>
        <tr>
            <td>Service Provider Current Balance</td>
            <td>{{$serviceProvider->balance}}</td>
        </tr>
        <tr>
            <td>Balance available for Cashout</td>
            <td>{{$serviceProvider->withdrawable_balance}}</td>
        </tr>
        <tr>
            <td>Phone</td>
            <td>{{$serviceProvider->phone}}</td>
        </tr>
        <tr>
            <td>Alt. Phone</td>
            <td>{{$serviceProvider->alt_phone}}</td>
        </tr>
        <tr>
            <td>MFS</td>
            <td>{{$serviceProvider->mfs_type}}</td>
        </tr>
        <tr>
            <td>MFS Number</td>
            <td>{{$serviceProvider->mfs_no}}</td>
        </tr>
        <tr>
            <td>MFS Number History</td>
            <td><a href="javaScript:;" data-toggle="modal" data-target="#serviceProviderMFSModal">MFS History</a></td>
        </tr>
        <tr>
            <td>Number of Comrade</td>
            <td>{{$serviceProvider->comrades->count()}}</td>
        </tr>
        <tr>
            <td>Number of Job Done</td>
            <td>{{$serviceProvider->total_job_done}}</td>
        </tr>
        <tr>
            <td>Service Provider Rating</td>
            <td>{{round($serviceProvider->ratings, 1)}}</td>
        </tr>
        <tr>
            <td>Natinal ID</td>
            <td>{{$serviceProvider->nid_no}}</td>
        </tr>
        <tr>
            <td>Trade License no</td>
            <td>{{ $serviceProvider->trade_lic_no }}</td>
        </tr>
        <tr>
            <td>TIN Number</td>
            <td>{{ $serviceProvider->tin_no }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>{{($serviceProvider->status==1)?'Active':'Inactive'}}</td>
        </tr>
    </table>
    <div class="panel-body text-center">
        @if($serviceProvider->media->count() > 0)
        <div class="row">
            @foreach($serviceProvider->media as $media)
            <div class="col-md-3">
                <p>{{ $media->replaced_type }}</p>
                <img data-toggle="modal" data-target="#Modal{{ $media->id }}" style="width:120px; height:120px; cursor: pointer;" src="{{ $media->photo_url }}" alt="{{ $media->replaced_type }}">
                <p><span class="badge badge-warning text-capitalize">{{ $media->status }}</span></p>
                @if($media->status == 'pending')
                <a href="{{ route('update_media_status.approve', $media->id) }}" class="btn btn-success">Approve</a>
                <a href="javaScript:;"  data-toggle="modal" data-target="#ModalDenyComment{{ $media->id }}" class="btn btn-danger">Deny</a>
                @endif
            </div>
            <div id="Modal{{ $media->id }}" class="modal fade text-center" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            @if (!empty($media->photo_url))
                            <img style="width:100%;" src="{{ $media->photo_url }}" alt="">
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="ModalDenyComment{{ $media->id }}" class="modal fade text-center" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form id="ModalDenyCommentForm{{ $media->id }}" method="POST" action="{{ route('update_media_status.deny', $media->id) }}">
                            @csrf
                            <div class="modal-body">
                            <div class="form-group">
                                <label for="deny_note{{ $media->id }}">Deny Note</label>
                                <textarea class="form-control" id="deny_note{{ $media->id }}" name="deny_note" rows="3" required></textarea>
                            </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Submit</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-center">No document uploaded yet</p>
        @endif
        
        @if(!empty($serviceProvider->user->mfsNumberHistory))
        <div id="serviceProviderMFSModal" class="modal fade text-center" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">MFS Number History</h4>
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th>SL</th>
                            <th>Mobile No</th>
                            <th>Created At</th>
                        </tr>
                        @foreach($serviceProvider->user->mfsNumberHistory as $key => $history)
                        <tr>
                            <td>{{ ($key+1)  }}</td>
                            <td>{{ $history->mfs_number }}</td>
                            <td>{{ $history->created_at }}</td>
                        </tr>
                        @endforeach
                    </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th colspan="7" class="text-left"><strong>Comrades</strong></th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Phone</th>
                <th>Total Job Done</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        @if(!empty($serviceProvider->comrades))
        <tbody>
            @foreach ($serviceProvider->comrades as $comrade)
            <tr>
                <td>{{$comrade->name}}</td>
                <td>{{$comrade->comrade_code}}</td>
                <td>{{$comrade->phone}}</td>
                <td>{{$comrade->total_job_done}}</td>
                <td>{{$comrade->approve == 1 ? 'Active' : 'Not Active' }}</td>
                <td>
                    @if($comrade->status == 0 && $comrade->approve == 1)
                    <a href="{{ route('comrade.active', $comrade->id) }}" class="btn btn-success btn-xs" onClick="return confirm('Are you sure you want to Active?')">Active</a>
                    @endif
                    @if($comrade->status == 1 && $comrade->approve == 1)
                    <a href="{{ route('comrade.inactive', $comrade->id) }}" class="btn btn-danger btn-xs" onClick="return confirm('Are you sure you want to In Active?')">In Active</a>
                    @endif
                    @if($comrade->approve == 2)
                    <a href="{{ route('comrade.approve', $comrade->id) }}" class="btn btn-primary btn-xs" onClick="return confirm('Are you sure you want to Approve?')">Approve</i></a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        @endif
    </table>
</div>
@section('scripts')

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>
{{-- <script src="{{asset('theme/js/Plugin/datatables.minfd53.js')}}"></script> --}}
@endsection
@endsection