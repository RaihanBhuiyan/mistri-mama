@if(!empty($selected_service_bits))
@foreach($selected_service_bits as $key => $service_bit)
<div class="row" id="service_select_panel" style="border-bottom:1px solid #c2cad8; padding: 5px 0;">
    <div class="col-md-9">
        <p class="m-0">{{ $service_bit['service_bit_name'] }}</p>
        <p class="m-0">Price : {{ $service_bit['total_price'] }}</p>
    </div>
    <div class="col-md-3 text-right">Qty : {{ $service_bit['qty'] }}</div>
</div>
@endforeach
@endif