<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\ServiceProvider;
use App\ServiceProviderTime;

class ServiceProviderTimeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function storeTime($times, $serviceProviderId)
    {
        // return $times;
        $serviceProviderTime = new ServiceProviderTime();

        $serviceProviderTime->service_provider_id = $serviceProviderId;
        $serviceProviderTime->day                 = $times['day'];
        $serviceProviderTime->start               = $times['start'];
        $serviceProviderTime->end                 = $times['end'];

        if ($serviceProviderTime->save()) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function deleteTime($serviceProviderId)
    {
        ServiceProviderTime::where(['service_provider_id' => $serviceProviderId])->delete();
        return true;
    }
}
