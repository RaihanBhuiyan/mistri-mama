<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\ServiceProviderDivision;
use Illuminate\Http\Request;

class ServiceProviderDivisionController extends Controller
{
    public static function storeDivision($division, $serviceProviderId)
    {

        $serviceProviderDivision = new ServiceProviderDivision();

        $serviceProviderDivision->service_provider_id = $serviceProviderId;
        $serviceProviderDivision->division_id         = $division;

        if ($serviceProviderDivision->save()) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public static function deleteDivision($serviceProviderId)
    {
        ServiceProviderDivision::where(['service_provider_id' => $serviceProviderId])->delete();
        return true;
    }
}
