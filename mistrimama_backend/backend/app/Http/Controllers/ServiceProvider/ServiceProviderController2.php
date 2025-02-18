<?php
namespace App\Http\Controllers\ServiceProvider;

use App\Account;
use App\Comrade;
use App\Http\Controllers\Account\AccountController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServiceProvider\ServiceProviderClusterController;
use App\Http\Controllers\ServiceProvider\ServiceProviderDivisionController;
use App\Http\Controllers\ServiceProvider\ServiceProviderTimeController;
use App\Http\Controllers\ServiceProvider\ServiceProviderZoneController;
use App\Http\Controllers\SiteConfigsController;
use App\ServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;
use App\Http\Resources\AllServicesResource;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ServiceProviderResource;
use App\Http\Resources\ComradeResource;
use App\Http\Resources\SchemeResource;
use App\Http\Resources\StatementResource;
use App\Setting;
use App\OrderReject;
use App\Order;
use App\OrderDetail;
use App\OrderSystem;
use App\RechargeRequest;
use App\Service;
use App\ServiceBit;
use App\User;
use App\ServiceProviderCategoryUpdateHistory;
use App\ServiceProviderService;
use App\Category;
use App\Division;
use App\BecomeServiceProvider;
use App\Cluster;
use App\Zone;
use App\Media;
use App\WithdrawRequest;
use Carbon\Carbon;
use App\SMS;
use App\MfsNumber;
use Illuminate\Support\Facades\Hash;
use File;
use URL;
use Madzipper;
use App\Scheme;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP; 

class ServiceProviderController2 extends Controller
{

    public $category_id;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type = null)
    {
        $sp = ServiceProvider::orderBy('id', 'desc');
        if(!empty($type))
        {
            $sp = $sp->where('type', $type);
        }
        $data['serviceprovider'] = $sp->get();
        return view('sp.index', $data);
    } 

    public function orderHistory()
    {  
        $orders = OrderDetail::orderBy('id', 'desc')
            ->get();
        //print_r($orders);
        return OrderDetailsResource::collection($orders);
    }      
}

