<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\ServiceProviderService;

class ServiceProviderServiceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function storeService($category, $serviceProviderId)
    {

        $serviceProviderService = new ServiceProviderService();

        $serviceProviderService->service_provider_id = $serviceProviderId;
        $serviceProviderService->category_id         = $category;

        if ($serviceProviderService->save()) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public static function deleteService($serviceProviderId)
    {
        ServiceProviderService::where(['service_provider_id' => $serviceProviderId])->delete();
        return true;
    }
}
