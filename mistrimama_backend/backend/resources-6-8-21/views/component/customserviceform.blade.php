
<table class="table" id="customOrderServiceBit">
    <tr>
        <th>Service Bit Name</th>
        <th>Offer Price</th>
        <th>Service Provider Income</th>
        <th class="text-center">Qty</th>
        <th>Action</th>
    </tr>
    <tr>
        <td>
            <input type="text" class="form-control" value="" id="custom_service_bit_name" name="custom_service_bit_name">
            <div class="invalid-feedback custom_service_bit_name">&nbsp;</div>
        </td>
        <td>
            <input type="number" class="form-control" value="" id="custom_price" name="custom_price">
            <div class="invalid-feedback custom_price">&nbsp;</div>
        </td>
        <td>
            <input type="number" class="form-control" value="" id="service_provider_price" name="service_provider_price">
            <div class="invalid-feedback service_provider_price">&nbsp;</div>
        </td>
        <td>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button class="btn btn-danger" type="button" id="custom_decrease" onclick="customDecrease()"><i class="icon md-minus"></i></button>
                </div>
                <input type="text" class="form-control text-center" id="custom_qty" placeholder="Qty" value="0" aria-label="">
                <div class="input-group-append">
                    <button class="btn btn-danger" type="button" id="custom_increase" onclick="customIncrease()"><i class="icon md-plus"></i></button>
                </div>
            </div>
        </td>
        <td>
            <button type="button" id="cartToCustomServiceBit" onclick="cartToCustomServiceBit({{ $category_id }})" class="btn btn-primary float-right ">Add</button>
        </td>
    </tr>
    <tr>
        <td colspan="3">Total Price</td>
        <td colspan="2"><strong id="withAdditionalPriceTotal">0</strong> BDT</td>
    </tr>
    @if(!empty($ordered_items))
    @foreach($ordered_items as $key => $ordered_item)
    <tr id="customServiceBit_{{ $key }}">
        <td colspan="3">
            <p class="m-0">{{ $ordered_item['service_bit_name'] }}</p>
            <p class="m-0">Price : <span id="serviceBitPrice_{{ $key }}">{{ $ordered_item['price'] }}</span></p>
        </td>
        <td class="text-center">{{ $ordered_item['qty'] }}</td>
        <td>
            <button type="button" id="removeServiceBitToCart_{{ $key }}" onclick="removeServiceBitToCart({{ (!empty($order_id)) ? $order_id : 0 }},{{ $key }},'{{ $ordered_item['service_id'] }}', 'custom')" class="btn btn-danger float-right "><i class="icon md-delete"></i></button>
        </td>
    </tr>
    @endforeach
    @endif
</table>