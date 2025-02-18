<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Account\AccountController;
use DB;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Order;
use App\OrderDetail;
use App\OrderSystem;
use App\ServiceProvider;
use App\ServiceSystem;
use App\Events\OrderFeedBackEvent;
use App\Http\Resources\OrderResource;
use App\Http\Controllers\Order\OrderSystemController;
use App\SMS;

class SslCommerzPaymentController extends Controller
{

    public function exampleEasyCheckout()
    {
        return view('exampleEasycheckout');
    }

    public function exampleHostedCheckout()
    {
        return view('exampleHosted');
    }

    public static function payment($data)
    {
        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] =  $data['total_amount']; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $data['order_no']; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $data['cus_name'];
        $post_data['cus_email'] = $data['cus_email'];
        $post_data['cus_add1'] = $data['cus_add1'];
        $post_data['cus_add2'] = $data['cus_add2'];
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $data['cus_phone'];
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "";
        $post_data['ship_add1'] = "";
        $post_data['ship_add2'] = "";
        $post_data['ship_city'] = "";
        $post_data['ship_state'] = "";
        $post_data['ship_postcode'] = "";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "";

        $post_data['shipping_method'] = "NO";

        $post_data['product_name'] = $data['order_no'];
        $post_data['product_category'] = $data['order_cat'];
        $post_data['product_profile'] = "non-physical-goods";

        # OPTIONAL PARAMETERS
        // $post_data['value_a'] = "ref001";
        // $post_data['value_b'] = "ref002";
        // $post_data['value_c'] = "ref003";
        // $post_data['value_d'] = "ref004";

        #Before  going to initiate the payment order status need to insert or update as Pending.
        Order::where('order_no', $post_data['tran_id'])->update([
            'pay_status' => 'pending'
        ]);
        
        $sslc = new SslCommerzNotification();
        
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');
        // return $payment_options;
        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();
        // $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());
        //  dd($validation);
        #Check order status in order tabel against the transaction id or order id.
        $order_detials = Order::where('order_no', $tran_id)->first();

        if ($order_detials->pay_status == 'pending') {
            $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());

            if ($validation == TRUE) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */


                // $order_id = Order::where('order_no', $tran_id)->first()->id;
                // $order = OrderDetail::find($order_id);

                // $serviceSys = OrderSystem::where('order_id', $order_id)->first()->id;
                // $ssupdate = OrderSystem::find($serviceSys);
                // $ssupdate->state = 4;
                // $ssupdate->save();

                // $orderUpdate = Order::find($order_id);
                // $orderUpdate->status = 5;
                // $orderUpdate->pay_type = 3;
                // $orderUpdate->pay_status = 'success';
                // $orderUpdate->save();

                // $serviceProvider = ServiceProvider::find($order->service_provider_id);

                // $totalAm = $order->total_price;
                // $totalAmWithoutDisc = $totalAm + $order->discount; // main price without any discount
                // $commissionAm = (($totalAmWithoutDisc * $order->commission) / 100);
                // $spEarnedAm = ($totalAmWithoutDisc - $commissionAm) - $order->discount;

                $order = Order::where('order_no', $tran_id)->first();
                $order->status = 5;
                $order->pay_type = 3;
                $order->pay_status = 'success';

                $orderSystem = OrderSystem::where('order_id', $order->id)->first();
                $orderSystem->state = 4;

                $order->save();
                $orderSystem->save();

                $orderDetails = OrderDetail::find($order->id);
                $serviceProvider = ServiceProvider::find($orderDetails->service_provider_id);

                OrderSystemController::orderBillPaymentReceived($orderDetails, $serviceProvider);
                
                broadcast(new OrderFeedBackEvent(new OrderResource($order)));
                echo "Transaction is successfully Completed....";
                echo "<script> window.location.href = '".env('FRONT_APP_URL')."/user' </script>";
                echo "Transaction is successfully Completed....";
                echo "<script> window.location.href = '".env('FRONT_APP_URL')."/user' </script>";
            } else {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                Here you need to update order status as Failed in order table.
                */
                $update_product = Order::where('order_no', $tran_id)->update([
                    'pay_status' => 'failed',
                    'status' => 4
                ]);
                echo "Transaction is Invalid...Returning to dashboard.";
                echo "<script>window.location.href = '".env('FRONT_APP_URL')."/user'</script>";
            }
        } else if ($order_detials->pay_status == 'processing' || $order_detials->pay_status == 'complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            echo "Transaction is successfully Completed....back to dashboard";
            echo "<script>window.location.href = '".env('FRONT_APP_URL')."/user'</script>";
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            echo "Transaction is Invalid...Returning to dashboard.";
            echo "<script>window.location.href = '".env('FRONT_APP_URL')."/user'</script>";
        }
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('order_no', $tran_id)
            ->select('order_no', 'pay_status')->first();
            
        if ($order_detials->pay_status == 'pending') {
            $update_product = DB::table('orders')
                ->where('order_no', $tran_id)
                ->update(['pay_status' => 'Failed']);
            echo "Transaction is Invalid...Returning to dashboard.";
            echo "<script>window.location.href = '".env('FRONT_APP_URL')."/user'</script>";
        } else if ($order_detials->pay_status == 'processing' || $order_detials->pay_status == 'complete') {
            echo "Transaction is already Successful";
        } else {
            echo "dd";
            echo "Transaction is Invalid...Returning to dashboard.";
            echo "<script>window.location.href = '".env('FRONT_APP_URL')."/user'</script>";
        }
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('order_no', $tran_id)
            ->select('order_no', 'pay_status')->first();

        if ($order_detials->pay_status == 'pending') {
            $update_product = DB::table('orders')
                ->where('order_no', $tran_id)
                ->update(['pay_status' => 'Failed']);
            echo "Transaction is Invalid...Returning to dashboard.";
            echo "<script>window.location.href = '".env('FRONT_APP_URL')."/user'</script>";
        } else if ($order_detials->pay_status == 'processing' || $order_detials->pay_status == 'complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid...Returning to dashboard.";
            echo "<script>window.location.href = '".env('FRONT_APP_URL')."/user'</script>";
        }
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('order_no', $tran_id)
                ->select('order_no', 'status', 'currency', 'amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($tran_id, $order_details->amount, $order_details->currency, $request->all());
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Failed']);

                    echo "validation Fail";
                }
            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }
}
