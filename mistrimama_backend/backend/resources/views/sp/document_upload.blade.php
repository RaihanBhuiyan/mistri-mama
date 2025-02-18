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
        <li class="breadcrumb-item active">Service Provider</li>
    </ol>
    <h1 class="page-title">Low Balance Service Provider</h1>

</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Low Balance Service Provider </h3>
    </div>
    <div class="panel-body">
        @if(!empty($services_providers))
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
                        <th>Action</th>
                        <th>Images</th>
                        <th>Code</th>
                        <th>Join Date</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Account Type</th>
                        <th>Zone</th>
                        <th>Cluster</th>
                        <th>Phone</th>
                        <th>MFS Number</th>
                        <th>Rating</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $i = 1
                    @endphp
                    @foreach($services_providers as $key => $sp)
                    @if(!empty($sp->serviceProvider))
                    <tr>
                        <td>{{($i++)}}</td>
                        <td>
                            <a title="Details" href="{{route('service-provider.show', $sp->serviceProvider->id)}}" class="btn btn-xs btn-warning"><i class="icon md-eye"></i></a>
                        </td>
                        <td>
                            <img style="width:60px; height:60px; border-radius: 100px;" src="{{$sp->serviceProvider->photo_url}}" alt="">
                        </td>
                        <td>{{$sp->serviceProvider->sp_code}}</td>
                        <td>{{ $sp->serviceProvider->created_at->format('Y-m-d') }}</td>
                        <td>{{strtoupper($sp->serviceProvider->type)}}</td>
                        <td>{{$sp->serviceProvider->name}}</td>
                        <td>
                            @if(!empty($sp->serviceProvider->service))
                                @foreach ($sp->serviceProvider->service as $s)
                                <span class="badge badge-info">{{$s->service->name}}</span>
                                @endforeach
                            @endif
                        </td>
                        <td style="text-transform:capitalize">{{$sp->serviceProvider->category}}</td>
                        <td>
                            @if(!empty($sp->serviceProvider->cluster))
                                @foreach ($sp->serviceProvider->cluster as $cluster)
                                <span class="badge badge-info">{{$cluster->cluster->name}}</span>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if(!empty($sp->serviceProvider->zone))
                                @foreach ($sp->serviceProvider->zone as $zone)
                                <span class="badge badge-info">{{$zone->zone->name}}</span>
                                @endforeach
                            @endif
                        </td>
                        <td>{{$sp->serviceProvider->phone}}</td>
                        <td>{{$sp->serviceProvider->mfs_no}}</td>
                        <td class="text-center">{{round($sp->serviceProvider->ratings, 1)}}</td>
                        <td>{{($sp->serviceProvider->status==1)?'Active':'Inactive'}}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
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
        buttons: [{ 
            extend: 'csv',
            text: 'Download'
        }]
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