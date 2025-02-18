<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Auditable
{
    use Notifiable, HasApiTokens, HasRoles, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'otp_code', 'ref_code', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'otp_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    public function serviceProvider()
    {
        return $this->hasOne('App\ServiceProvider');
    }

    public function client()
    {
        return $this->hasOne('App\Client');
    }
    public function admin()
    {
        return $this->hasOne('App\Admin');
    }

    public function comrade()
    {
        return $this->hasOne('App\Comrade');
    }

    public function relNotifications()
    {
        return $this->hasMany('App\Notification', 'notifiable_id', 'id')->orderBy('created_at', 'desc');
    }

    public function mfsNumberHistory()
    {
        return $this->hasMany('App\MfsNumber', 'user_id', 'id')->orderBy('id', 'desc');
    }

    public function mfsType()
    {
        if ($this->getRoleNames()->first() == 'esp' || $this->getRoleNames()->first() == 'fsp') {
            return $this->serviceProvider->mfs_type;
        } else if ($this->getRoleNames()->first() == 'client' || $this->getRoleNames()->first() == null) {
            return $this->client ? $this->client->mfs_type : '';
        }
    }

    public function mfsNo()
    {
        if ($this->getRoleNames()->first() == 'esp' || $this->getRoleNames()->first() == 'fsp') {
            return $this->serviceProvider->mfs_no;
        } else if ($this->getRoleNames()->first() == 'client' || $this->getRoleNames()->first() == null) {
            return $this->client ? $this->client->mfs_no : '';
        }
    }

    public function address()
    {
        if ($this->getRoleNames()->first() == 'esp' || $this->getRoleNames()->first() == 'fsp') {
            return $this->serviceProvider ? $this->serviceProvider->address : '';
        } else if ($this->getRoleNames()->first() == 'client' || $this->getRoleNames()->first() == null) {
            return $this->client ? $this->client->address : '';
        } else if ($this->getRoleNames()->first() == 'comrade') {
            return $this->comrade ?  $this->comrade->address : '';
        }
    }

    public function photo()
    {
        if(in_array($this->getRoleNames()->first(), ['esp','fsp'])){
            return $this->serviceProvider->photo_url;
        }
        if(in_array($this->getRoleNames()->first(), ['client'])){
            return $this->client ? $this->client->photo_url : '';
        }
        if(in_array($this->getRoleNames()->first(), ['comrade'])){
            return $this->comrade ? $this->comrade->photo_url : '';
        }
    }
}
