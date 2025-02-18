@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
@endsection

@section('content')
<!-- Panel Full Example -->
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Order</li>
    </ol>
    <h1 class="page-title">Order</h1>

</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Assign Service Provider [ Order ID # {{ $order->order_no }}]</h3>
    </div>
    @if(!empty($service_providers))
    <div class="example table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Action</th>
                    <th>Service Provider ID</th>
                    <th>Service Provider Type</th>
                    <th>Service Provider Name</th>
                    <th>Service Provider Category</th>
                    <th>Service Provider Account Type</th>
                    <th>Service Provider Zone</th>
                    <th>Service Provider Cluster</th>
                    <th>Service Provider Current Balance</th>
                    <th>Service Provider Phone</th>
                    <th>Number of Comrade</th>
                    <th>Number of Job Done</th>
                    <th>Service Provider Rating</th>
                </tr>
            </thead>
            @foreach ($service_providers as $key => $provider)
            <tr class="{{ ($provider->id == $selected_service_provider_id) ? 'bg-success' : '' }}">
                <td>{{($key+1)}}</td>
                <td>
                    <a href="{{ route('allocating.sp', [$order_id, $provider->id]) }}" class="btn btn-primary btn-xs" onClick="return confirm('Are you sure you want to assign?')">Assign</a>
                </td>
                <td>{{$provider->sp_code}}</td>
                <td>{{strtoupper($provider->type)}}</td>
                <td>{{$provider->name}}</td>
                <td>
                    @if(!empty($provider->service))
                        @foreach ($provider->service as $s)
                        <span class="badge badge-info">{{$s->service->name}}</span>
                        @endforeach
                    @endif
                </td>
                <td style="text-transform:capitalize">{{$provider->category}}</td>
                <td>
                    @if(!empty($provider->cluster))
                        @foreach ($provider->cluster as $cluster)
                        <span class="badge badge-info">{{$cluster->cluster->name}}</span>
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(!empty($provider->zone))
                        @foreach ($provider->zone as $zone)
                        <span class="badge badge-info">{{$zone->zone->name}}</span>
                        @endforeach
                    @endif
                </td>
                <td class="text-center">{{$provider->balance}}</td>
                <td>{{$provider->phone}}</td>
                <td class="text-center">{{$provider->no_of_active_comrade}}</td>
                <td class="text-center">{{$provider->total_job_done}}</td>
                <td class="text-center">{{$provider->ratings}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif
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
    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a image here or click',
            'replace': 'Drag and drop or click to replace',
            'remove':  'Remove',
            'error':   'Ooops, something wrong happended.'
        }
    });
</script>


@endsection
@endsection