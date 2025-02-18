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
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Add New Promocode</h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('promocode.store')}}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label class="form-control-label" for="promo_type">Select Promo Type</label>
                    <select id="promo_type" class="form-control" name="promo_type">
                        <option value="percentage" {{ (old('promo_type') == 'percentage') ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed_amount" {{ (old('promo_type') == 'fixed_amount') ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                    @if($errors->has('promo_type'))
                        <div class="text-danger">{{ $errors->first('promo_type') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-8">
                    <label class="form-control-label">Name<span class="required">*</span></label>
                    <input type="text" class="form-control" value="{{ old('promocode') }}" name="promocode" placeholder="Promocode Name">
                    @if($errors->has('promocode'))
                        <div class="text-danger">{{ $errors->first('promocode') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group fixed_amount" style="display:{{ (old('promo_type') == 'fixed_amount') ? 'block' : 'none' }}">
                <label class="form-control-label">Cash<span class="required">*</span></label>
                <input type="number" class="form-control" value="{{ old('cash') }}" name="cash" placeholder="Cash">
                @if($errors->has('cash'))
                    <div class="text-danger">{{ $errors->first('cash') }}</div>
                @endif
            </div>

            <div class="form-row percentage" style="display:{{ (old('promo_type') == 'fixed_amount') ? 'none' : 'flex' }}">
                <div class="form-group col-md-6">
                    <label class="form-control-label">Percentance<span class="required">*</span></label>
                    <input type="number" class="form-control" value="{{ old('percent') }}" name="percent" placeholder="Percent">
                    @if($errors->has('percent'))
                        <div class="text-danger">{{ $errors->first('percent') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label class="form-control-label">Up To<span class="required">*</span></label>
                    <input type="number" class="form-control" value="{{ old('up_to') }}" name="up_to" placeholder="Up to amount">
                    @if($errors->has('up_to'))
                        <div class="text-danger">{{ $errors->first('up_to') }}</div>
                    @endif
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-control-label">Details<span class="required">*</span></label>
                <textarea class="form-control" name="details" value="{{ old('details') }}" id="details" placeholder="Write your promocode description here" rows="5"></textarea>
                
                @if($errors->has('details'))
                    <div class="text-danger">{{ $errors->first('details') }}</div>
                @endif
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="form-control-label">Validity Date<span class="required">*</span></label>
                    <input type="date" data-plugin="datepicker" class="form-control" value="{{ old('validity_date') }}" name="validity_date" placeholder="validity_date">
                    
                    @if($errors->has('validity_date'))
                        <div class="text-danger">{{ $errors->first('validity_date') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label class="form-control-label">Uses Count<span class="required">*</span></label>
                    <input type="number" class="form-control" value="{{ old('uses_count') ? old('uses_count') : 1 }}" name="uses_count" placeholder="uses_count">
                    @if($errors->has('uses_count'))
                        <div class="text-danger">{{ $errors->first('uses_count') }}</div>
                    @endif
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
        </form>
    </div>
</div>

@section('scripts')

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

<script>

$(document).ready( function () {
    $("#promo_type").change(function() {
        var promo_type = $(this).val();
        if(promo_type == 'percentage')
        {
            $('.percentage').show();
            $('.fixed_amount').hide();
        }
        if(promo_type == 'fixed_amount')
        {
            $('.percentage').hide();
            $('.fixed_amount').show();
        }
    });
});
</script>

@endsection
@endsection