@extends('layouts.app') 
@section('styles')
<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-bs4/dataTables.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/tables/datatable.minfd53.css?v4.0.1')}}">
@endsection

@section('content')
<!-- Panel Full Example -->
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Manage Comrade</li>
        <li class="breadcrumb-item active">Comrades</li>
    </ol>
    <h1 class="page-title">Manage Comrade</h1>

    <div class="page-header-actions"> 
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{ route('comrade.export') }}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Download</span>
        </a> 
    </div>

</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Comrades</h3>
    </div>
    <div class="panel-body">
        @if(!empty($comrades))
        <div class="row">
            <div class="input-group col-md-6">
                <div class="input-group-prepend">
                    <span class="input-group-text btn btn-default btn-sm" id="basic-addon1">Report Start Date</span>
                </div>
                <input class="form-control form-control-sm" name="min" id="min" type="date">
            </div>
            <div class="input-group col-md-6">
                <div class="input-group-prepend">
                    <span class="input-group-text btn btn-default btn-sm" id="basic-addon1">Report End Date</span>
                </div>
                <input class="form-control form-control-sm" name="max" id="max" type="date">
            </div>
        </div>
        <div class="example table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th class="">Action</th>
                        <th>Images</th>
                        <th>Comrade Joining Date</th>
                        <th>Comrade ID</th>
                        <th>Comrade Name</th>
                        <th>Comrade Phone</th>
                        <th>Number of Job Done by This Comrade</th>
                        <th>Service Provider ID</th>
                        <th>Service Provider Name</th>
                        <th>Service Provider Category</th>
                        <th>Service Provider Zone</th>
                        <th>Service Provider Cluster</th>
                        <th>Service Provider Phone</th>
                        <th>Number of Comrade under this Service Provider</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Approve</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comrades as $key =>  $comrade)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td class="text-right">
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
                        <td>
                            <img style="width:60px; height:60px; border-radius: 100px;" src="{{$comrade->photo_url}}" alt="" data-toggle="modal" data-target="#comradeProfilePhotoModal{{ $comrade->id }}">
                        </td>
                        <td>{{$comrade->created_at->format('Y-m-d')}}</td>
                        <td>{{$comrade->comrade_code}}</td>
                        <td>{{$comrade->name}}</td>
                        <td>{{$comrade->phone}}</td>
                        <td>{{$comrade->total_job_done}}</td>
                        <td>{{ (!empty($comrade->serviceProvider)) ? $comrade->serviceProvider->sp_code : 'Service Provider Not Found' }}</td>
                        <td>{{ (!empty($comrade->serviceProvider)) ? $comrade->serviceProvider->name : 'Service Provider Not Found'}}</td>
                        <td>
                            @if(!empty($comrade->serviceProvider->service))
                                @foreach ($comrade->serviceProvider->service as $s)
                                <span class="badge badge-info">{{$s->service->name}}</span>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if(!empty($comrade->serviceProvider->cluster))
                                @foreach ($comrade->serviceProvider->cluster as $cluster)
                                <span class="badge badge-info">{{$cluster->cluster->name}}</span>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if(!empty($comrade->serviceProvider->zone))
                                @foreach ($comrade->serviceProvider->zone as $zone)
                                <span class="badge badge-info">{{$zone->zone->name}}</span>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ (!empty($comrade->serviceProvider)) ? $comrade->serviceProvider->phone : 'Service Provider Not Found' }}</td>
                        <td class="text-center">{{ (!empty($comrade->serviceProvider)) ? $comrade->serviceProvider->no_of_active_comrade : 'Service Provider Not Found' }}</td>
                        <td class="text-center">{{ (!empty($comrade->serviceProvider)) ? $comrade->serviceProvider->rating : 'Service Provider Not Found' }}</td>
                        <td>{{($comrade->status==1)?'Active':'Inactive'}}</td>
                        <td>{{($comrade->approve==1) ? 'Approved' : 'Not Approved'}}</td>
                        <td>
                            <a href="javaScript:;" data-toggle="modal" data-target="#serviceProviderNIDFBModal{{ $comrade->id }}">View NID front/ NID backce</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@if(!empty($comrades))
@foreach ($comrades as $key =>  $comrade)
<div id="serviceProviderNIDFBModal{{ $comrade->id }}" class="modal fade text-center" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                @if (!empty($comrade->nid_front))
                <img style="width:100%;" src="{{asset($comrade->nid_front_url)}}" alt="">
                @endif
                @if (!empty($comrade->nid_back))
                <img style="width:100%;" src="{{asset($comrade->nid_back_url)}}" alt="">
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="comradeProfilePhotoModal{{ $comrade->id }}" class="modal fade text-center" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                @if (!empty($comrade->photo))
                <img style="width:100%;" src="{{asset($comrade->photo_url)}}" alt="">
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
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

<script src="{{asset('theme/vendor/datatables.net/jquery.dataTablesfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-bs4/dataTables.bootstrap4fd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-fixedheader/dataTables.fixedHeader.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-fixedcolumns/dataTables.fixedColumns.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-rowgroup/dataTables.rowGroup.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-scroller/dataTables.scroller.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-responsive/dataTables.responsive.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-responsive-bs4/responsive.bootstrap4.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-buttons/dataTables.buttons.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-buttons/buttons.html5.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-buttons/buttons.flash.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-buttons/buttons.print.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-buttons/buttons.colVis.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/datatables.net-buttons-bs4/buttons.bootstrap4.minfd53.js?v4.0.1')}}"></script>

<script>
$(document).ready( function () {

    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var min = $('#min').val();
            var max = $('#max').val();
            
            var startDate = data[1];
            console.log(startDate);
            if (min == null && max == null) { return true; }
            if (min == '' && max == '') { return true; }
            if (min == null && startDate <= max) { return true;}
            // if (min == startDate) { return true;}
            // if (min <= startDate && startDate >= min) { return true;}
            if(max == null && startDate >= min) {return true;}
            if (startDate <= max && startDate >= min) { return true; }
            // return false;
        }
    );
    
    $('#min').change( function() {
        table.draw();
    });
    $('#max').change( function() {
        table.draw();
    });

    const table = $('.table').DataTable({
        dom: 'Bfrtip',
        buttons: []
    });
    

    // $('.table thead tr').clone(true).appendTo( '.table thead' );
    // $('.table thead tr:eq(1) th').each( function (i) {
    //     var title = $(this).text();
    //     $(this).html( '<input type="text" class="form-control form-control-sm" placeholder="Search '+title+'" />' );

    //     $( 'input', this ).on( 'keyup change', function () {
    //         if ( table.column(i).search() !== this.value ) {
    //             table.column(i).search( this.value ).draw();
    //         }
    //     });
    // });

});

</script>

@endsection
@endsection