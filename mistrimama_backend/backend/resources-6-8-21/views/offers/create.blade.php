@extends('layouts.app')
@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/select2/select2.min.css')}}">

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
        <li class="breadcrumb-item active">Offer Create</li>
    </ol>
    <h1 class="page-title">Offer Create</h1>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Offer Create</h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('offer.store')}}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="form-control-label">Title<span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-bus" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" value="{{ old('title') }}" name="title" placeholder="Offer Title" required="">
                    </div>
                </div>
                <div class="form-group col-md-2">
                    <label class="form-control-label">Offer For<span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-bus" aria-hidden="true"></i>
                        </span>
                        <select class="form-control" value="{{ old('offers_for') }}" name="offers_for" required="">
                            <option value="esp">ESP</option>
                            <option value="fsp">FSP</option>
                            <option value="client">Client</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Expire Date<span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-bus" aria-hidden="true"></i>
                            </span>
                            <input type="date" class="form-control" value="{{ old('expire_date') }}" name="expire_date" placeholder="Expire Date" required="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-control-label" for="offers_type">Offer Type
                    <span class="required">*</span>
                </label>
                <select class="form-control " name="offers_type" id="offers_type">
                    <option value="general_offer" {{ (old('offers_type') == 'general_offer') ? 'selected' : '' }}>General Offer</option>
                    <option value="referral_offer" {{ (old('offers_type') == 'referral_offer') ? 'selected' : '' }}>Referral Page Offer</option>
                    <option value="quick_order_offer" {{ (old('offers_type') == 'quick_order_offer') ? 'selected' : '' }}>Quick Order Offer</option>
                    <option value="discount_offer" {{ (old('offers_type') == 'discount_offer') ? 'selected' : '' }}>Discount Offer</option>
                </select>
            </div>
            <div class="form-group" {{ (old('offers_type') == 'quick_order_offer') ? 'style=display:block' : 'style=display:none' }} id="display_quick_order_offer">
                <label class="form-control-label" for="find_service">Find Your Service
                    <span class="required">*</span>
                </label>
                <select class="form-control" style="width:100%;" name="find_service" id="find_service">
                    <option value="">Select Service</option>
                </select>
            </div>
            <div class="row" {{ (old('offers_type') == 'discount_offer') ? 'style=display:flex' : 'style=display:none' }} id="display_discount_offer">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="service_id" class="form-control-label">Service Name
                            <span class="required">*</span>
                        </label>
                        <select class="form-control" required="" name="discount_offer[service_id]" id="service_id">
                            @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach($category->services as $service)
                                <option value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label" for="service_bit_id">Service Bit
                            <span class="required">*</span>
                        </label>
                        <select class="form-control " name="discount_offer[service_bit_id]" id="service_bit_id">
                            <option value="general_offer">Select Service Bit</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label" for="promocode">Promocode
                            <span class="required">*</span>
                        </label>
                        <select class="form-control " name="discount_offer[promocode]" id="promocode">
                            <option value="general_offer">Select Promo Code</option>
                            @foreach($promo_code as $code)
                            <option value="{{$code->promocode}}">{{$code->promocode}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-control-label">Description<span class="required">*</span></label>
                <div class="input-group">
                    <textarea type="text" class="form-control" name="description" placeholder="Description" required="">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <!-- Example Default -->
                <div class="example-wrap m-0">
                    <h4 class="example-title mb-1">Upload Offer Image <span class="required">*</span></h4>
                    <div class="example m-0" id="offer_imagex">
                        <input type="file" class="dropify" id="offer_image" name="offer_image" data-plugin="dropify" data-default-file="" />
                    </div>
                    @if ($errors->has('offer_image'))
                        <div class="text-danger">{{ $errors->first('offer_image') }}</div>
                    @endif
                </div>
                <!-- End Example Default -->
            </div>
            <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
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
<script src="{{asset('theme/assets/select2/select2.min.js')}}"></script>

<script>
$(document).ready(function() {
    var drEvent = $('.dropify').dropify();
    drEvent.on('dropify.afterClear', function(event, element){
        $(this).next('input[type="text"]').remove();
    });

    $('#find_service').select2();
    $("#offers_type").change(function(){
        var type =  $(this).val();

        $("#display_quick_order_offer").css('display', 'none');
        $("#display_discount_offer").css('display', 'none');
        if(type == 'quick_order_offer')
        {
            $("#display_quick_order_offer").css('display', 'block');
        }
        if(type == 'discount_offer')
        {
            $("#display_discount_offer").css('display', 'flex');
        }
    });

    $("#service_id").change(function(){
        var service_id = $(this).val();
        $.ajax({
            url: "{{ url('api/get-service-bit') }}/" + service_id,
            type: 'get',
            success: function(response) {
                var sel = '<option value="">Select Service Bit</option>';
                $.each(response, function(index, value){
                    sel += '<option value="'+index+'">'+value+'</option>';
                });
                $("#service_bit_id").html(sel);
            },
            error: function (error) {
            }
        });
    });

    $("#find_service").select2({
        ajax:{
            type: "POST",
            url: "{{ url('api/quickorderitems') }}",
            dataType: 'json',
            data: function (term) {
                return {
                    find: term.term
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: item,
                            id: item
                        }
                    })
                };
            }
        }
    })
});
</script>

@endsection
@endsection