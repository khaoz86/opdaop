<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class AdminCreditLog extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $uuid = Uuid::uuid4();
            $model->id = $uuid->toString();
        });
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id_from', 'user_id_to',
    ];

    public function user_from()
    {
        return $this->belongsTo('App\User', 'user_id_from')->select('id', 'username', 'group_id')->withDefault([
            'username' => '###',
        ]);
    }

    public function user_to()
    {
        return $this->belongsTo('App\User', 'user_id_to')->select('id', 'username', 'group_id')->withDefault([
            'username' => '###',
        ]);
    }

}
