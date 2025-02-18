<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function itemStatusChange($itemId)
    {
        $orderItem = OrderItem::find($itemId);
        $orderItem->status == 0 ? $orderItem->status = 1 : $orderItem->status = 0;
        if ($orderItem->save()) {
            return response(['message' => 'Work Done']);
        }
    }

    public function itemQuantityChange($itemId, $qty)
    {
        $orderItem = OrderItem::find($itemId);
        $orderItem->quantity = $qty;
        $orderItem->total_price = 0; // only for mutate in model
        if ($orderItem->save()) {
            return response(['message' => 'success', 'total_price' => $orderItem->total_price]);
        }
    }
}
