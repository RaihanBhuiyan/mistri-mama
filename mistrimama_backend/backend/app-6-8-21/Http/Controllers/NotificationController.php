<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GeneralNotification;
use App\Notifications\PlacedOrder;
use App\Notifications\RegisteredServiceProvider;
use App\ServiceProvider;
use App\Events\OrderEvent;
use App\Events\OrderPaymentEvent;
use App\Http\Resources\OrderResource;
use App\Http\Resources\NotificationResource;
use App\Order;
use App\Events\NotificationFrontendEvent;
use App\OrderDetail;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        return response()->json([
            'unread_notifications' => auth()->user()->relNotifications->whereNull('read_at')->count(),
            'notifications' => NotificationResource::collection(auth()->user()->relNotifications)
        ]);
    }
    public function index()
    {
        // $user = User::where(['id' => auth()->user()->id])->first();
        // $user->unreadNotifications->markAsRead();
        $data['notifications'] = auth()->user()->relNotifications;
        return view('notifications.index', $data);
    }

    public static function orderSubmitNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['esp', 'fsp']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "New Service order has been placed by user. Order no #".$value->order_no."",
            'path' => '/shokolkaaj'
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));

        $two = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing']);
        })->get();
        $two_notify = Notification::send($two, new GeneralNotification([
            'title' => "New Service order has been placed by user. Order no #".$value->order_no."",
            'path' => route('order.index')
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));
    }

    public static function orderAcceptNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Order Allocated by ESP (".$value->serviceProvider->name." - ".$value->serviceProvider->phone.").",
            'path' => NULL
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function allocatedComradeNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Order accepted by Service Provider(".$value->serviceProvider->name." - ".$value->serviceProvider->phone.").",
            'path' => NULL
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
        
        $two_notify = Notification::send($value->comrade->user, new GeneralNotification([
            'title' => "You have a new service order.",
            'path' => NULL
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));

        // check if guest user are order
        if(!empty($value->order->user))
        {
            $three_notify = Notification::send($value->order->user, new GeneralNotification([
                'title' => "Your order has been allocated to ".$value->comrade->name.".",
                'path' => NULL
            ]));
            broadcast(new NotificationFrontendEvent($three_notify));
        }
    }

    public static function orderPaymentReceivedNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Order payment.",
            'path' => NULL
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));

        if(!empty($value->ref_code))
        {
            $earned_point = round((($value->total_price * 30) / 100));
            $two = User::where("ref_code", $value->ref_code)->first();
            $two_notify = Notification::send($two, new GeneralNotification([
                'title' => "You have earned ".$earned_point." referal point.",
                'path' => NULL
            ]));
            broadcast(new NotificationFrontendEvent($two_notify));
        }

        $three_notify = Notification::send($value->serviceProvider->user, new GeneralNotification([
            'title' => "Service order #".$value->order_no." payment received.",
            'path' => '/ayerbiboroni'
        ]));
        broadcast(new NotificationFrontendEvent($three_notify));

        if(!empty($value->comrade))
        {
            $four_notify = Notification::send($value->comrade->user, new GeneralNotification([
                'title' => "Service order #".$value->order_no." payment received.",
                'path' => NULL
            ]));
            broadcast(new NotificationFrontendEvent($four_notify));
        }
    }
    
    public static function orderMistrimamaCashReceiveNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['admin', 'marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => $value['details'],
            'path' => route('order.show', $value['ref_key'])
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function customOrderSubmitNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['esp', 'fsp']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "New Service order has been placed from Mistri Mama. Order no #".$value->order_no."",
            'path' => '/shokolkaaj'
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));

        $two = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing', 'operation']);
        })->get();
        $two_notify = Notification::send($two, new GeneralNotification([
            'title' => "New Service order has been placed by SRPU . Order no #".$value->order_no."",
            'path' => route('order.index')
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));
    }

    public static function cashoutRequestNotification($value)
    {
        if($value['trx_for'] == 'client')
        {
            $type = 'User';
        }
        if($value['trx_for'] == 'service_provider')
        {
            $type = 'Service provider';
        }

        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['admin', 'accountant']);
        })->get();
        
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "New Cash out request BDT ".$value['amount']." by ".$type.".",
            'path' => route('withdraw.request')
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));

        $two = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing', 'operation']);
        })->get();
        
        $two_notify = Notification::send($two, new GeneralNotification([
            'title' => "New Cash out request BDT ".$value['amount']." by ".$type.".",
            'path' => NULL
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));
    }
    
    public static function cashoutAcceptNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['accountant']);
        })->get();
        
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Cash out request Approved.",
            'path' => ($value == 'withdraw') ? route('withdraw.request') : route('cash_out.request')
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));

        $two = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['operation', 'marketing']);
        })->get();
        
        $two_notify = Notification::send($two, new GeneralNotification([
            'title' => "Cash out request Approved.",
            'path' => NULL
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));
    }

    public static function cashoutApproveNotification($value, $user)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['admin', 'operation', 'marketing']);
        })->get();
        
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Cash disbursment from Finance.",
            'path' => NULL
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));

        $two = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['accountant']);
        })->get();
        
        $two_notify = Notification::send($two, new GeneralNotification([
            'title' => "Cash out request Approved.",
            'path' => ($value == 'withdraw') ? route('withdraw.history') : route('cash_out.history')
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));
        
        $three_notify = Notification::send($user, new GeneralNotification([
            'title' => "Your Cash out request approved..",
            'path' => ($value == 'withdraw') ? "/ayerbiboroni" : NULL
        ]));
        broadcast(new NotificationFrontendEvent($three_notify));
    }


    public static function rechargeRequestNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['admin', 'marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "New Recharge request BDT ".$value['amount']." by Service Provider.",
            'path' => NULL,
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));

        $two = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['accountant']);
        })->get();
        $two_notify = Notification::send($two, new GeneralNotification([
            'title' => "New Recharge request BDT ".$value['amount']." by Service Provider.",
            'path' => route('recharge.index'),
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));
    }
    
    public static function rechargeRequestApproveNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['admin', 'marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Rechare approved from Finance.",
            'path' => route('recharge.history'),
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));

        $two_notify = Notification::send($value, new GeneralNotification([
            'title' => "Rechage succssfully .",
            'path' => '/ayerbiboroni',
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));
    }

    public static function offerNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['admin', 'marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "New offer from MBD.",
            'path' => NULL,
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
        
        $two = User::whereHas("roles", function ($q) use ($value) {
            $q->whereIn("name", [$value['offers_for']]);
        })->get();
        $two_notify = Notification::send($two, new GeneralNotification([
            'title' => "New offer (".$value['title'].").",
            'path' => ($value['offers_for'] == 'esp' || $value['offers_for'] == 'fsp') ? "/offerdekhun" : "/offers",
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));
    }

    public static function comradeRegistrationNotification()
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "New Comrade add by ESP.",
            'path' => route('comrade.index'),
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function comradeStatusUpdateFromServiceProviderNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Comrade ".$value." by ESP.",
            'path' => route('comrade.index'),
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function comradeApproveDenyNotification($value, $comrade)
    {
        $status = ($value['status'] == 1) ? "Approve" : "Deny";
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Comrade ".$status." by ESP.",
            'path' => route('comrade.index'),
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));

        $two_notify = Notification::send($comrade->serviceProvider->user, new GeneralNotification([
            'title' => "Comrade ".$status." by Mistri Mama.",
            'path' => NULL,
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));
    }

    public static function comradeStatusUpdateNotification($value, $comrade)
    {
        $status = ($value['status'] == 1) ? "Active" : "In Active";
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Comrade ".$status." by ESP.",
            'path' => route('comrade.index'),
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));

        $two_notify = Notification::send($comrade->serviceProvider->user, new GeneralNotification([
            'title' => "Comrade ".$status." by Mistri Mama.",
            'path' => NULL,
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));
    }

    public static function quickOrderNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "".$value['phone']." quick order has been placed.",
            'path' => route('quickorder.index'),
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function contactUsNotification()
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "New query has been submitted.",
            'path' => NULL,
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function resumeNotification()
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "New resume has been submitted.",
            'path' => route('careers.index'),
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function createServiceProviderNotification()
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['marketing', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "New Service Provider request.",
            'path' => route('service-provider.index'),
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function upgradeServiceProviderCategoryNotification()
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['admin', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Service provider request to upgrade his account.",
            'path' => route('service-provider.upgrade-request'),
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function documentUploadNotification($id, $status)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['admin', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Service provider request to update his ".$status.".",
            'path' => route('service-provider.show', $id),
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function documentUploadApproveNotification($media)
    {
        $one = User::where("id", $media->user_id)->first();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Your ".strtoupper(str_replace("_", " ", $one->type))." update request has been Approve.",
            'path' => "/spprofile"
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function documentUploadDenyNotification($media)
    {
        $one = User::where("id", $media->user_id)->first();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Your ".strtoupper(str_replace("_", " ", $media->type))." photo update request has been Deny.",
            'path' => "/spprofile"
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));
    }

    public static function schemeNotification($value)
    {
        $one = User::whereHas("roles", function ($q) {
            $q->whereIn("name", ['admin', 'marketing', 'accountant', 'operation']);
        })->get();
        $one_notify = Notification::send($one, new GeneralNotification([
            'title' => "Scheme bonus disburse to Service Provider (".$value->name." - ".$value->sp_code.").",
            'path' => route('transactions.index'),
        ]));
        broadcast(new NotificationFrontendEvent($one_notify));

        $two_notify = Notification::send($value->user, new GeneralNotification([
            'title' => "Scheme Bonus received.",
            'path' => "/scheme",
        ]));
        broadcast(new NotificationFrontendEvent($two_notify));
    }
    
    public function eventFire()
    {
        $order = new OrderResource(OrderDetail::first());
        broadcast(new OrderPaymentEvent($order));
    }

    public function markAsReadNotification()
    {
        $auth_user_id = auth()->user()->id;
        $user = User::where(['id' => $auth_user_id])->first();

        if($user->unreadNotifications->count() > 0)
        {
            $user->unreadNotifications->markAsRead();
            return auth()->user()->unreadNotifications->count();
        }
        return 0;
    }
}
