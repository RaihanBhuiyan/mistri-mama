@if(!empty($service_bits))
@foreach($service_bits as $key => $service_bit)
@php
$check = (array_key_exists($service_bit->id, $ordered_items)) ? true : false;
@endphp
<div class="row" id="service_select_panel" style="border-bottom:1px solid #c2cad8; padding: 5px 0;" title="{{ $service_bit->service_id }}{{ $service_bit->id }}">
    <div class="col-md-8">
        <p class="m-0">{{ $service_bit->name }}</p>
        <p class="m-0">Price : <span id="serviceBitPrice_{{ $service_bit->id }}">{{ $service_bit->price }}</span></p>
    </div>
    <div class="col-md-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <button class="btn btn-danger" @if($check) disabled @endif type="button" id="decrease_{{ $service_bit->id }}" onclick="decrease({{ $service_bit }})"><i class="icon md-minus"></i></button>
            </div>
            <input type="text" class="form-control text-center" @if($check) disabled @endif id="qty_{{ $service_bit->id }}" placeholder="Qty" value="{{ ($check) ? $ordered_items[$service_bit->id]['qty'] : 1 }}" aria-label="">
            <div class="input-group-append">
                <button class="btn btn-danger" @if($check) disabled @endif type="button" id="increase_{{ $service_bit->id }}" onclick="increase({{ $service_bit }})"><i class="icon md-plus"></i></button>
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <button type="button" @if($check) style="display:none" @else style="display:block" @endif id="cartToServiceBit_{{ $service_bit->id }}" onclick="cartToServiceBit({{ $service_bit->id }},{{ $service_bit->service_id }})" class="btn btn-primary float-right ">Add</button>
        <button type="button" @if($check) style="display:block" @else style="display:none" @endif id="removeServiceBitToCart_{{ $service_bit->id }}" onclick="removeServiceBitToCart({{ (!empty($order_id)) ? $order_id : 0 }},{{ $service_bit->id }},{{ $service_bit->service_id }}, 'regular')" class="btn btn-danger float-right "><i class="icon md-delete"></i></button>
    </div>
</div>
@endforeach
@endif