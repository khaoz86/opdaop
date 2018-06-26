<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'server_access_id',
    ];

    protected $casts = [
        'limit_bandwidth' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function server_access()
    {
        return $this->belongsTo('App\ServerAccess');
    }

    public function subscriptions()
    {
        return $this->belongsToMany('App\Subscription')->orderBy('subscription_id');
    }

    public function online_users()
    {
        return $this->hasMany('App\OnlineUser');
    }

}