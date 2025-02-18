@if(!empty($services))
@php
$custom_service_bit_id = $category_id."00";
@endphp
<div class="row" style="border-top:1px solid #c2cad8; padding: 5px 0;">
    <div class="col-md-9">Custom Service</div>
    <div class="col-md-3">
        <a class="btn btn-warning float-right" href="javaScript:;" onclick="getSelectedServiceBit({{$category_id}}, '{{$custom_service_bit_id}}', 'custom')">Add</a>
    </div>
</div>

<div class="" id="service_section_{{$custom_service_bit_id}}">
    @if(!empty($exists_order) && array_key_exists($custom_service_bit_id, $exists_order['order_items']))
    @foreach($exists_order['order_items'][$custom_service_bit_id]['items'] as $key => $item)
    <div class="row" id="service_select_panel" style="border-bottom:1px solid #c2cad8; padding: 5px 0;">
        <div class="col-md-9">
            <p class="m-0">{{ $item['service_bit_name'] }}</p>
            <p class="m-0">Price : {{ $item['total_price'] }}</p>
        </div>
        <div class="col-md-3 text-right">Qty : {{ $item['qty'] }}</div>
    </div>
    @endforeach
    @endif
</div>


@foreach($services as $key => $service)
<div class="row" style="border-top:1px solid #c2cad8; padding: 5px 0;">
    <div class="col-md-9">{{ $service->name }}</div>
    <div class="col-md-3">
        <a class="btn btn-primary float-right" href="javaScript:;" onclick="getSelectedServiceBit({{$category_id}}, {{ $service->id }}, 'regular')">Add</a>
    </div>
</div>

<div class="" id="service_section_{{ $service->id }}">
    @if(!empty($exists_order) && array_key_exists($service->id, $exists_order['order_items']))
    @foreach($exists_order['order_items'][$service->id]['items'] as $key => $item)
    <div class="row" id="service_select_panel" style="border-bottom:1px solid #c2cad8; padding: 5px 0;">
        <div class="col-md-9">
            <p class="m-0">{{ $item['service_bit_name'] }}</p>
            <p class="m-0">Price : {{ $item['total_price'] }}</p>
        </div>
        <div class="col-md-3 text-right">Qty : {{ $item['qty'] }}</div>
    </div>
    @endforeach
    @endif
</div>


@endforeach
@endif