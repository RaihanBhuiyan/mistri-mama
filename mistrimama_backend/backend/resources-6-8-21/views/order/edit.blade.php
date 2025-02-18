@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
@endsection

@section('content')
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Order</li>
        <li class="breadcrumb-item active">Edit Order</li>
    </ol>
    <h1 class="page-title">Order No #{{ $order->order_no }}</h1>
</div>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <form id="order_form" method="POST" action="javaScript:;">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $order->name }}" placeholder="Name">
                            <div class="invalid-feedback name">&nbsp;</div>
                        </div>
                        <div class="form-group col-md-6">  
                            <label>Phone:</label>
                            <div class="input-group"> 
                                <span class="input-group-addon">+88</span>
                                <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ $order->phone }}" placeholder="Phone Number">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cluster">Area:</label>
                        <select name="cluster" id="cluster" class="form-control">
                            <option value="">{{ $order->area }}</option>
                        </select>
                        <div class="invalid-feedback cluster">&nbsp;</div>
                    </div>
                    
                    @if($order->outside_charge > 0)
                    <div class="alert alert-danger" role="alert">N.B. Outside City extra charge BDT {{ $site_config['area_charge'] }} will be added.</div>
                    @endif

                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea name="address" id="address" rows="3" class="form-control">{{ $order->address }}</textarea>
                        <div class="invalid-feedback address">&nbsp;</div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="order_date">Date:</label>
                            <select name="order_date" id="order_date" class="form-control">
                            <option value="">{{ $order->date_with_day }}</option>
                            </select>
                            <div class="invalid-feedback order_date">&nbsp;</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="order_time">Time:</label>
                            <select name="order_time" id="order_time" class="form-control">
                            <option value="">{{ $order->time }}</option>
                            </select>
                            <div class="invalid-feedback order_time">&nbsp;</div>
                        </div>
                    </div>

                    @if($order->emergency_charge > 0)
                    <div class="alert alert-danger" role="alert">N.B. For emergency service hour ({{ $site_config['office_end_time'] }} to {{ $site_config['office_start_time'] }}) an additional BDT {{ $site_config['schedule_charge'] }} will be added.</div>
                    @endif

                    <div class="form-group">
                        <label for="ref_code">Referal Code:</label>
                        <input type="text" class="form-control" id="ref_code" name="ref_code" value="{{ $order->ref_code }}" placeholder="Enter Referal Code">
                        <div class="invalid-feedback ref_code">&nbsp;</div>
                    </div>
                    <div class="form-group input-group">
                        <select class="form-control" id="promocode" name="promocode">
                            <option value="">Select Promo Code</option>
                            @if(!empty($promocodes))
                            @foreach ($promocodes as $promocode)
                            <option value="{{$promocode->promocode}}">{{$promocode->promocode}}</option>
                            @endforeach
                            @endif
                        </select>
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" id="applyPromoCode">Apply Promo Code</button>
                        </span>
                    </div>
                    <div class="alert alert-danger is_discount" role="alert" style="display:none"></div>
                    <div class="promocode_validation_messages">&nbsp;</div>

                    <div class="form-group">
                        <label for="note">Note:</label>
                        <textarea name="note" id="note" rows="3" class="form-control">{{ $order->comments }}</textarea>
                        <div class="invalid-feedback note">&nbsp;</div>
                    </div>
                    <button class="btn btn-block btn-primary" style="margin-top:15px;" type="button" id="place_order_btn">Update Order</button>
                </form>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select name="category" class="form-control" id="category">
                        <option value="{{$order->category_id}}">{{$order->category_name}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" id="amount" name="amount" value="{{ $order->total_price }}">
                    <p class="mb-0" style="font-weight:bold">Service Price: <span id="service_total" style="float:right;">{{ $order->total_price }} BDT</span></p>
                    <p class="mb-0 schedule_charge" style="font-weight:bold; {{ ($order->emergency_charge > 0) ? 'display:block' : 'display:none' }}">For emergency service hour: <span id="schedule_charge" style="float:right;">{{ $order->emergency_charge }} BDT</span></p>
                    <p class="mb-0 area_charge" style="font-weight:bold; {{ ($order->outside_charge > 0) ? 'display:block' : 'display:none' }}">For Outside City charge: <span id="area_charge" style="float:right;">{{ $order->outside_charge }} BDT</span></p>
                    <p class="mb-0 discount" style="font-weight:bold; {{ ($order->discount > 0) ? 'display:block' : 'display:none' }}">Discount: <span id="discount" style="float:right;">(-) {{ $order->discount }} BDT</span></p>
                    <p class="mb-0" style="font-weight:bold">Total Price: <span id="grand_total" style="float:right;">{{ (($order->total_price + $order->emergency_charge + $order->outside_charge) - $order->discount) }} BDT</span></p>
                </div>

                <div class="form-group">
                    <label for="">Services:</label>
                    <div id="service_box" style="height:480px; overflow: auto;"><em>Please select an category for chose your service</em></div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="services_modal" tabindex="-1" role="dialog" aria-labelledby="servicesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="servicesModalLabel">Select Services</span>
                <button type="button" class="close" style="padding: 0 15px; margin: 0;" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="displayServicesBit"></div>
                <div>Total Price: <strong id="displayApproxPrice">0</strong> BDT</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" id="cartServices" class="btn btn-primary">Save </button>
            </div>
        </div>
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
<script>

$(document).ready( function () {
    $("#category").change(function() {
        var cat_id = $(this).val();
        $('#service_box').html("");
        $("#displayApproxPrice").text(0);
        if(cat_id == '')
        {
            return false;
        }
        $.ajax({
            type: "GET",
            url: "{{ url('custom/order/services') }}/" + cat_id + "/edit",
            dataType: 'json',
            success: function(data) {
                console.log(data);
                $('#service_box').append(data);
            }
        });
    }).change();;

    $('#cartServices').click(function(){
       var service_id = $(this).val();
       $('#service_section_' + service_id).html("");
       $.ajax({
            type: "GET",
            url: "{{ url('custom/order/service-bit/cart-selected') }}/" + service_id,
            dataType: 'json',
            success: function(data) {
                $('#service_section_' + service_id).html(data);
                $('#services_modal').modal('hide');
            }
        });
    });

    $('#place_order_btn').click(function(e){
        $(".form-control").removeClass('is-invalid');
        $(".invalid-feedback").text("&nbsp;");
        $.ajax({
            url: "{{ route('order.update', $order->id) }}",
            type: 'put',
            data: $('#order_form').serialize(),
            success: function(response) {
                toastr.success(response.message);
            },
            error: function (error) {
                toastr.error(error.responseJSON.message);
                if(error.status == 422)
                {
                    $.each(error.responseJSON.errors, function(index, value){
                        $("#"+ index +"").addClass('is-invalid');
                        $(".invalid-feedback."+ index +"").text(value[0]);
                    });
                }
            }
        });
    });

    $("#applyPromoCode").click(function(){
        var promocode = $('#order_form #promocode').val();
        var phone_no = $('#order_form #phone_no').val();
        var amount = $('#amount').val();
        $(".promocode_validation_messages").html("");
        $(".promocode_validation_messages").css('display', 'nopne');
        $('.is_discount').hide();
        $('.discount').hide();
        $.ajax({
            url: "{{ route('apply-promo-codes') }}",
            type: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                'promo_code': promocode,
                'phone': phone_no,
                'amount': amount
            },
            success: function(response) {
                $(".is_discount").show();
                $(".is_discount").text('You have got ' + response.discount + ' BDT discount');
                $(".discount").show();
                $("#discount").text('(-) ' + response.discount + ' BDT');
                grandTotal();
            },
            error: function (error) {
                toastr.error(error.responseJSON.message);
                if(error.status == 422)
                {
                    $("#promocode").addClass('is-invalid');
                    $(".promocode_validation_messages").css('display', 'block');
                    $.each(error.responseJSON.errors, function(index, value){
                        $(".promocode_validation_messages").append('<p class="mb-0">'+value[0]+'</p>');
                    });
                }
            }
        });
    })
});

function grandTotal()
{
    var service_total = $("#service_total").text().split(" BDT");
    var schedule_charge = $("#schedule_charge").text().split(" BDT");
    var area_charge = $("#area_charge").text().split(" BDT");
    var discount = $("#discount").text().split(" BDT");
    console.log(discount);

    var st = (service_total[0] == '') ? 0 : parseInt(service_total[0]);
    var sc = (schedule_charge[0] == '') ? 0 : parseInt(schedule_charge[0]);
    var ac = (area_charge[0] == '') ? 0 : parseInt(area_charge[0]);
    var ds = (discount[0] == '') ? 0 : parseInt(discount[0].split("(-) ")[1]);
    console.log( st + ' ' + sc + ' ' + ac +' '+ ds );
    var grand_total = ( (st + sc + ac) - ds )
    $("#grand_total").text(grand_total + ' BDT');
}

function getSelectedServiceBit( category_id, service_id, type ){
    $('#displayServicesBit').html('');
    $.ajax({
        type: "GET",
        url: "{{ url('custom/order/service-bit') }}/" + category_id + "/" + service_id + "/" + type,
        dataType: 'json',
        success: function(data) {
            $('#services_modal').modal('show');
            $("#cartServices").attr('value', service_id);
            $('#displayServicesBit').html(data);
        }
    })
}

function cartToServiceBit(service_bit_id, service_id)
{
    var qty = $('#qty_' + service_bit_id).val();

    $.ajax({
        url: "{{ route('custom.order.service.bit.cart') }}",
        type: 'post',
        data: {
            "_token": "{{ csrf_token() }}",
            "service_bit_id": service_bit_id,
            "service_id": service_id,
            "qty": qty,
            "type": "regular"
        },
        dataType: 'json',
        success: function(response) {
            $('#cartToServiceBit_' + service_bit_id).hide();
            $('#removeServiceBitToCart_' + service_bit_id).show();
            $('#decrease_' + service_bit_id).attr('disabled', 'disabled');
            $('#qty_' + service_bit_id).attr('disabled', 'disabled');
            $('#increase_' + service_bit_id).attr('disabled', 'disabled');
            $("#displayApproxPrice").text(response.total_price);
            $("#service_total").text(response.total_price + ' BDT');
            $("#amount").val(response.total_price);
            grandTotal();
        }
    });
}

function cartToCustomServiceBit(category_id)
{
    var service_bit_name = $('#custom_service_bit_name').val();
    var price = $('#custom_price').val();
    var service_provider_price = $('#service_provider_price').val();
    var qty = $('#custom_qty').val();

    if(qty == 0)
    {
        return false;
    }

    $.ajax({
        url: "{{ route('custom.order.service.bit.cart') }}",
        type: 'post',
        data: {
            "_token": "{{ csrf_token() }}",
            "category_id": category_id,
            "service_bit_name": service_bit_name,
            "price": price,
            "service_provider_price": service_provider_price,
            "qty": qty,
            "type": "custom"
        },
        dataType: 'json',
        success: function(response) {
            getSelectedServiceBit(category_id, ''+category_id+'00', 'custom');
            $('#custom_service_bit_name').val("");
            $('#custom_price').val("");
            $('#service_provider_price').val("");
            $('#custom_qty').val(0);
            $("#withAdditionalPriceTotal").text(0.00);
            
            $("#displayApproxPrice").text(response.total_price);
            $("#service_total").text(response.total_price + ' BDT');
            $("#amount").val(response.total_price);
            grandTotal();
        },
        error: function (error) {
            $(".invalid-feedback").hide();
            toastr.error(error.responseJSON.message);
            if(error.status == 422)
            {
                $.each(error.responseJSON.errors, function(index, value){
                    console.log(index);
                    $(".invalid-feedback."+index+"").show();
                    $(".invalid-feedback."+index+"").text(value[0]);
                });
            }
        }
    });
}


function removeServiceBitToCart(order_id, service_bit_id, service_id, type){
    $.ajax({
        url: "{{ route('custom.order.service.bit.cart.remove') }}",
        type: 'post',
        data: {
            "_token": "{{ csrf_token() }}",
            "order_id": order_id,
            "service_bit_id": service_bit_id,
            "service_id": service_id,
        },
        dataType: 'json',
        success: function(response) {
            if(type == 'regular')
            {
                $('#cartToServiceBit_' + service_bit_id).show();
                $('#removeServiceBitToCart_' + service_bit_id).hide();
                $('#decrease_' + service_bit_id).removeAttr('disabled');
                $('#qty_' + service_bit_id).val(1);
                $('#qty_' + service_bit_id).removeAttr('disabled');
                $('#increase_' + service_bit_id).removeAttr('disabled');
            }
            if(type == 'custom')
            {
                $('#customServiceBit_' + service_bit_id).remove();
            }

            $("#displayApproxPrice").text(response.total_price);
            $("#service_total").text(response.total_price + ' BDT');
            $("#amount").val(response.total_price);
            grandTotal();
        },
        error: function (error) {
            toastr.error(error.responseJSON.message);
        }
    });
}

function customIncrease()
{
    var custom_qty = parseInt($("#custom_qty").val());
    custom_qty++;
    if($("#custom_price").val() == '')
    {
        custom_qty = 0
    }
    $("#custom_qty").val(custom_qty);
    getTotalcustomApproxPric(custom_qty)
}
function customDecrease()
{
    var custom_qty = parseInt($("#custom_qty").val());
    if(custom_qty != 0)
    {
        custom_qty--;
    }
    $("#custom_qty").val(custom_qty);
    getTotalcustomApproxPric(custom_qty)
}
function getTotalcustomApproxPric(custom_qty)
{
    var price = parseInt(($("#custom_price").val() == '') ? 0 : $("#custom_price").val());
    var totalPrice = price * custom_qty;
    $("#withAdditionalPriceTotal").text(parseFloat(totalPrice).toFixed( 2 ))
}

function increase(service_bit){
    var selected_qty = $('#qty_' + service_bit.id).val();
    
    selected_qty++;

    $('#qty_' + service_bit.id).val(selected_qty);
    var total_price = 0;
    total_price += parseInt(service_bit.price) * selected_qty;
    $("#serviceBitPrice_" + service_bit.id).text(parseFloat(total_price).toFixed( 2 ));
}

function decrease(service_bit){
    var selected_qty = $('#qty_' + service_bit.id).val();
    if (selected_qty != 1) {
        selected_qty--;
    }
    $('#qty_' + service_bit.id).val(selected_qty);
    var total_price = 0;
    total_price += parseInt(service_bit.price) * selected_qty;
    $("#serviceBitPrice_" + service_bit.id).text(parseFloat(total_price).toFixed( 2 ));
}

</script>
<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
@endsection
@endsection