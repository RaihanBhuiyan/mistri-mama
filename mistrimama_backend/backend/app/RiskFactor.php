<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiskFactor extends Model
{
    protected $fillable = [
        'title', 'particulars', 'type', 'created_at', 'updated_at'
    ];

    // particular_list
    public function getParticularListAttribute()
    {
        $json_decode_particulars = json_decode($this->particulars);
        $string = "<ul><li>";
        $string .= str_replace(",", "</li><li>", implode(",", $json_decode_particulars));
        $string .= "</li></ul>";
        return $string;
    }
}
