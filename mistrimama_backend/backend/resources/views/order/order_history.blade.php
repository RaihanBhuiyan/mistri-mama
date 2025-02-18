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
        <li class="breadcrumb-item">Order</li>
        <li class="breadcrumb-item active">Order History</li>
    </ol>
    <h1 class="page-title">Order</h1>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Order History</h3>
    </div>
    <div class="panel-body">
        @if(!empty($orders))
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
                        <th>Job Number</th>
                        <th>User Type</th>
                        <th>User Name</th>
                        <th>User Phone Number</th>
                        <th>User Zone</th>
                        <th>User Address</th>
                        <th>Service Zone</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>Emergency Hour Charge</th>
                        <th>Outside DMC Charge</th>
                        <th>Discount Amount</th>
                        <th>Billed Amount</th>
                        <th>Grand Payable</th>
                        <th>Service Date</th>
                        <th>Service Time</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td>
                            <a href="{{route('order.show',$order->id)}}" class="badge badge-info btn btn-xs">Details</a>
                        </td>
                        <td>{{$order->order_no}}</td>
                        <td style="text-transform:capitalize">@if(!empty($order->user)) Client @else Guest @endif</td>
                        <td>{{$order->name}}</td>
                        <td>{{$order->phone}}</td>
                        <td>@if(!empty($order->user)) 
                            @if(!empty($order->user->client)) 
                            @if(!empty($order->user->client->cluster)) 
                            {{ $order->user->client->cluster->name }} 
                            @endif
                            @endif
                            @else Guest @endif</td>
                        <td>@if(!empty($order->user)) 
                            @if(!empty($order->user->client)) 
                            {{ $order->user->client->address }} 
                            @endif 
                            @else Guest @endif</td>
                        <td>{{$order->area}}, {{$order->address}}</td>
                        <td>{{ $order->total_unit }}</td>
                        <td>{{ $order->total_unit_price }}</td>
                        <td>{{ $order->emergency_charge }}</td>
                        <td>{{ $order->outside_charge }}</td>
                        <td>{{ $order->discount }}</td>
                        <td>{{ $order->total_price }}</td>
                        <td>{{ $order->grant_total }}</td>
                        <td>{{$order->date}}</td>
                        <td>{{$order->time}}</td>
                        <td>{{$order->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center panel-title">There is no orders found</p>
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
        buttons: [
            { 
                extend: 'csv',
                text: 'Download'
            }
        ]
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