<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ServiceProviderCategoryUpdateHistory extends Model
{
    use SoftDeletes;
    protected $table = "service_provider_category_upgrade_histories";
    protected $fillable = [
        'user_id', 'previous_category', 'requested_category',
    ];
}
