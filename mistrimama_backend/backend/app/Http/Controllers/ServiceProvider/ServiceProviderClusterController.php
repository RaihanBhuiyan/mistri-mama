<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\ServiceProviderCluster;
use Illuminate\Http\Request;

class ServiceProviderClusterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function storeCluster($cluster, $serviceProviderId)
    {

        $serviceProviderCluster = new ServiceProviderCluster();

        $serviceProviderCluster->service_provider_id = $serviceProviderId;
        $serviceProviderCluster->cluster_id          = $cluster;

        if ($serviceProviderCluster->save()) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public static function deleteCluster($serviceProviderId)
    {
        ServiceProviderCluster::where(['service_provider_id' => $serviceProviderId])->delete();
        return true;
    }
}
