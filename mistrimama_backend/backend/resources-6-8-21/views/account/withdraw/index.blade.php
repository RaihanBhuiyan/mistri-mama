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
        <li class="breadcrumb-item">Account</li>
        <li class="breadcrumb-item active">Withdraw</li>
    </ol>
    <h1 class="page-title">Withdraw</h1>
    @if (auth()->user()->hasAnyRole(['accountant']))
    <div class="page-header-actions">
        @if(!empty($withdraw_requests))
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{ route('withdraw.export') }}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Download</span>
        </a>
        @endif
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" data-toggle="collapse" href="#importWithdrawRequest"
            role="button" aria-expanded="false" aria-controls="importWithdrawRequest">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Upload</span>
        </a>
    </div>
    @endif
</div>

<div class="collapse" id="importWithdrawRequest">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">Upload Withdraw Request file</h3>
        </div>
        <div class="panel-body">
            <form action="{{route('withdraw.import')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-0">
                    <div class="input-group">
                        <span class="input-group-addon">Upload file here</span>
                        <input type="file" name="withdraw_request_file" class="form-control" placeholder="" accept=".xlsx, .xls, .csv">
                        <button type="submit"  class="input-group-addon bg-green-600 text-white">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Service Provider Withdraw Request</h3>
    </div>
    <div class="panel-body">
        @if(!empty($withdraw_requests))
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
                        <th>Cashout Request Date & Time</th>
                        <th>Service Provider ID</th>
                        <th>Service Provider Joining Date</th>
                        <th>Service Provider Type</th>
                        <th>Service Provider Name</th>
                        <th>Service Provider Category</th>
                        <th>Service Provider Current Balance</th>
                        <th>Cashout Amount</th>
                        <th>Cashout Receive MFS Number</th>
                        <th>MFS Type</th>
                        <th>Transaction ID</th>
                        <th>MFS Number History</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($withdraw_requests as $key => $request)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td>
                            <a href="{{route('service_privider.transactions', $request->user_id)}}" class="btn btn-xs btn-success">View Transactions of This SP</a>
                            @if($getRoleNames=='admin')
                                @if($request->approve==0)
                                    <a href="{{route('withdraw.request.adminapprove', $request->id)}}" class="btn btn-xs btn-success">Approve</a>
                                    <a href="{{route('withdraw.deny', $request->id)}}" onClick="return confirm('Sure to deny withdraw request?')" class="btn btn-xs btn-danger">Deny</a>
                                @elseif($request->approve==1)
                                    Approved
                                @else
                                    Deny
                                @endif
                            @else
                                <a href="javaScript:;" data-toggle="modal" data-target="#approveModal{{ $request->id }}" class="btn btn-xs btn-success">Approve</a>
                                <a href="{{route('withdraw.deny', $request->id)}}" onClick="return confirm('Sure to deny withdraw request?')" class="btn btn-xs btn-danger">Deny</a>
                                
                                <form action="{{route('withdraw.approve', $request->id)}}" method="POST">
                                @csrf
                                <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1" role="dialog" aria-labelledby="approveModal{{ $request->id }}Label">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="approveModal{{ $request->id }}Label">Approve Withdraw Request</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group text-left">
                                                    <label for="transaction_no" class="control-label">Transaction Number</label>
                                                    <input type="text" class="form-control" id="transaction_no" name="transaction_no">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            @endif
                        </td>
                        <td>{{$request->created_at}}</td>
                        <td>{{$request->user->serviceProvider->sp_code}}</td>
                        <td>{{$request->user->created_at->format('d-M-Y')}}</td>
                        <td class="text-capitalize">{{$request->user->serviceProvider->type}}</td>
                        <td>{{$request->user->name}}</td>
                        <td>
                            @if(!empty($request->user->serviceProvider->service))
                                @foreach ($request->user->serviceProvider->service as $s)
                                <span class="badge badge-info">{{$s->service->name}}</span>
                                @endforeach
                            @endif
                        </td>
                        <td>{{$request->user->serviceProvider->balance}}</td>
                        <td>{{$request->amount}}</td>
                        <td>{{$request->mfs_number}}</td>
                        <td>{{$request->mfs}}</td>
                        <td></td>
                        <td>
                            <a href="javaScript:;" data-toggle="modal" data-target="#serviceProviderMFSModal{{ $request->id }}">MFS History</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @foreach ($withdraw_requests as $key => $request)
        @if(!empty($request->user->mfsNumberHistory))
        <div id="serviceProviderMFSModal{{ $request->id }}" class="modal fade text-center" tabindex="-1">
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
                        @foreach($request->user->mfsNumberHistory as $key => $history)
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
        @endforeach
        @else
        <p class="text-center panel-title">There is no withdraw request found</p>
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