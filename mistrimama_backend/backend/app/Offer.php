<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Offer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'title', 'offers_for','description','expire_date', 'offer_image', 'offers_type', 'alt_description'
    ];

    // offer_image_url
    public function getOfferImageUrlAttribute($value)
    {
        return (!empty($this->offer_image)) ? env('APP_URL').'/upload/offers/'. $this->offer_image : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }
}
