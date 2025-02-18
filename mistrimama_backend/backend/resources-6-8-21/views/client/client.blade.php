@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
<!-- Plugins For This Page -->
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-bs4/dataTables.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.minfd53.css?v4.0.1')}}">

<!-- Page -->
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/tables/datatable.minfd53.css?v4.0.1')}}">
@endsection

@section('content')
<!-- Panel Full Example -->
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Client</li>
    </ol>
    <h1 class="page-title">Client</h1>
    <div class="page-header-actions">

    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">All Client</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="input-group col-md-5">
                <div class="input-group-prepend">
                    <span class="input-group-text btn btn-default btn-sm" id="basic-addon1">Report Start Date</span>
                </div>
                <input class="form-control form-control-sm" name="min" id="min" type="date">
            </div>
            <div class="input-group col-md-5">
                <div class="input-group-prepend">
                    <span class="input-group-text btn btn-default btn-sm" id="basic-addon1">Report End Date</span>
                </div>
                <input class="form-control form-control-sm" name="max" id="max" type="date">
            </div>
            <div class="input-group col-md-2">
                <button type="button" id="btn_search" value="Search" class="btn btn-primary">Search</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table" id="example">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Action</th>
                        <th>Status</th>
                        <th>Images</th>
                        <th>User Type</th>
                        <th>Openning Date</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>User Zone</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Last Order Info</th>
                        <th>Total Order</th>
                        <th>Total Cancel Order</th>
                        <th>Popular services</th>
                        <th>Total Spent</th>
                        <th>Average Spent on Service</th>
                        <th>User Refer Code</th>
                        <th>Total Refer</th>
                        <th>Total RP earning</th>
                        <th>Current RP Balance</th>
                        <th>RP equivalent Cash</th>
                        <th>Total Cash Out</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

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

    var t = $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        "paging": true,
        'searching': true,
        "ajax": {
            "url": "{{ route('clients.filter') }}",
            "type": "POST",
            "data": function(data){
                data._token = $('meta[name=csrf-token]').attr("content");
                data.from_date = $('#min').val();
                data.to_date = $('#max').val();
            },
        },
        "columns": [
            { "data":"id", "sortable": false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { "data": "status", "class": "",
                render: function (data, type, row, meta) {
                    if(data.status == 1)
                    {
                    return "<a href='{{ env('APP_URL') }}/client/toggle/status/"+data.user_id+"' class='btn btn-xs btn-danger'>In Active</a>";
                    }
                    if(data.status == 0)
                    {
                    return "<a href='{{ env('APP_URL') }}/client/toggle/status/"+data.user_id+"' class='btn btn-xs btn-success'>Active</a>";
                    }
                }
            },
            { "data": "status", "class": "",
                render: function (data, type, row, meta) {
                    if(data.status == 1)
                    {
                    return "Active";
                    }
                    if(data.status == 0)
                    {
                    return "In Active";
                    }
                }
            },
            { "data": "client_photo", "sortable": false,
                render: function (data, type, row, meta) {
                    return "<img style='width:60px; height:60px; border-radius: 100px; cursor:pointer' src='"+data+"' alt=''>";
                }
            },
            { "data": "type", "class": "text-capitalize" },
            { "data": "created_at" },
            { "data": "name" },
            { "data": "phone" },
            { "data": "cluster_name" },
            { "data": "address" },
            { "data": "email"},
            { "data": "last_order_info", "sortable": false,
                render: function (data, type, row, meta) {
                    return (data != '') ? "<a href='{{ env('APP_URL') }}/order/"+data+"'>Details</a>" : 'No Order';
                }
            },
            { "data": "total_order" },
            { "data": "total_cancel_order" },
            { "data": "popular_services" },
            { "data": "client_total_spent" },
            { "data": "average_client_spent" },
            { "data": "ref_code" },
            { "data": "total_ref_order" },
            { "data": "total_earn_point" },
            { "data": "available_reward_point" },
            { "data": "available_reward_balance" },
            { "data": "total_cashout" },
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: 'Bfrtip',
        buttons: [{ 
            extend: 'csv',
            text: 'Download'
        }, 'pageLength' ],
    });

    $('#btn_search').click(function(){
        t.draw();
    });
    $('#example tbody').on('click', 'tr td img', function () {
        $('#clientProfilePhotoModal').modal('show');
        $("#clientProfilePhotoModal img").attr("src", $(this).attr('src'));
    });
});
</script>
@endsection
<div id="clientProfilePhotoModal" class="modal fade text-center" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img style="width:100%;" src="" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection