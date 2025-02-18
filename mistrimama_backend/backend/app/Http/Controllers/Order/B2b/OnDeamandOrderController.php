<?php

namespace App\Http\Controllers\Order\B2b;

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderDetail;
use App\OrderSystem;
use App\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnDeamandOrderController extends Controller
{
    public function outstandingBalanceAdd($orderid)
    {
        $order = OrderDetail::find($orderid);

        $account = new AccountController();
        if ($account->outstandingBalanceAdd($order->user_id, $order)) {
            $serviceProvider = ServiceProvider::find($order->service_provider_id);

            $totalAm = $order->total_price;
            $totalAmWithoutDisc = $totalAm + $order->discount; // main price without any discount
            $commissionAm = (($totalAmWithoutDisc * $order->commission) / 100);
            $spEarnedAm = ($totalAmWithoutDisc - $commissionAm) - $order->discount;

            if (AccountController::mistrimamaCommissionIncome($commissionAm, $order) && AccountController::serviceProviderIncome($serviceProvider, $order, $spEarnedAm)) {

                try{
                    DB::beginTransaction();
                    $orderMain = Order::find($orderid);
                    $orderMain->status = 5;
                    $orderMain->save();
                    $orderSystem = OrderSystem::where('order_id', $orderid)->first();
                    $orderSystem->state = 4;
                    $orderSystem->save();
                    DB::commit();
                    return 1;
                }catch(\Exception $e){
                    DB::rollback();
                }

            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}
