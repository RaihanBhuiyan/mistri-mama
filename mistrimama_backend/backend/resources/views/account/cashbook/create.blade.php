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
        <li class="breadcrumb-item active">Transaction Entry</li>
    </ol>
    <h1 class="page-title">Transaction Entry</h1>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Transaction Entry</h3>
    </div>
    <div class="panel-body">
        <form method="POST" action="{{ route('transaction.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="heading_type">Transaction Heading Type</label>
                        <select name="heading_type" id="heading_type" class="form-control {{ $errors->has('heading_type') ? 'is-invalid' : '' }}">
                            <option value="">Select an option</option>
                            <option value="investment" {{ (old('heading_type') == 'investment') ? 'selected' : '' }}>Investment</option>
                            <option value="expenses" {{ (old('heading_type') == 'expenses') ? 'selected' : '' }}>Expenses</option>
                            <option value="revenue" {{ (old('heading_type') == 'revenue') ? 'selected' : '' }}>Revenue</option>
                        </select>
                        @if ($errors->has('heading_type'))
                            <div class="invalid-feedback">{{ $errors->first('heading_type') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="transaction_heading">Transaction Heading</label>
                        <select name="transaction_heading" id="transaction_heading" class="form-control {{ $errors->has('transaction_heading') ? 'is-invalid' : '' }}">
                            <option value="">Select an option</option>
                        </select>
                        @if ($errors->has('transaction_heading'))
                            <div class="invalid-feedback">{{ $errors->first('transaction_heading') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control {{ $errors->has('date') ? 'is-invalid' : '' }}" name="date">
                        @if ($errors->has('date'))
                            <div class="invalid-feedback">{{ $errors->first('date') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="payment_mode">Payment Mode</label>
                        <select name="payment_mode" id="payment_mode" class="form-control {{ $errors->has('payment_mode') ? 'is-invalid' : '' }}">
                            <option value="">Select an option</option>
                            <option value="Cash">Cash</option>
                            <option value="Check">Check</option>
                            <option value="Bank Deposit">Bank Deposit</option>
                            <option value="Digital Payment">Digital Payment</option>
                            <option value="Others">Others</option>
                        </select>
                        @if ($errors->has('payment_mode'))
                            <div class="invalid-feedback">{{ $errors->first('payment_mode') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" name="amount">
                        @if ($errors->has('amount'))
                            <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="details">Details</label>
                <textarea class="form-control {{ $errors->has('details') ? 'is-invalid' : '' }}" name="details" id="details" cols="30" rows="5"></textarea>
                @if ($errors->has('details'))
                    <div class="invalid-feedback">{{ $errors->first('details') }}</div>
                @endif
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
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
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>


<script src="{{asset('theme/assets/js/Site.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/asscrollable.minfd53.js?v4.0.1')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

<script>
$(document).ready( function () {
    $("#heading_type").change(function(){
        var heading_type = $(this).val();
        $.ajax({
            type: "GET",
            url: "{{ url('account/headings') }}/"+ heading_type,
            dataType: 'json',
            success: function(data) {
                var output = '<option value="">Select an option</option>';
                for(var i=0; i < data.length; i++){
                    output += '<option value="'+ data[i].id +'">'+ data[i].title +'</option>';
                }
                $('#transaction_heading').html(output);
            }
        })
    });
    $("#heading_type").change();
});
</script>
@endsection
@endsection

