<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\ServiceProviderZone;

class ServiceProviderZoneController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function storeZone($zone, $serviceProviderId)
    {

        $serviceProviderZone = new ServiceProviderZone();

        $serviceProviderZone->service_provider_id = $serviceProviderId;
        $serviceProviderZone->zone_id             = $zone;

        if ($serviceProviderZone->save()) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public static function deleteZone($serviceProviderId)
    {
        ServiceProviderZone::where(['service_provider_id' => $serviceProviderId])->delete();
        return true;
    }
}
