<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    protected $fillable = [
        'name', 'mobile', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];


    /**********************/
    /***** Relations *****/
    /**********************/

    public function inds()
    {
        return $this->hasMany(Ind::class);
    }

    /**
     * @param $status string
     * @return object|null
     */
    public function orgs($status = null)
    {
        $result = null;

        if ($status) {
            switch ($status) {
                case 'pending':
                    return $this->hasMany(Org::class)->where('is_pending', '=', 1);
                    break;
                case 'approved':
                    return $this->hasMany(Org::class)->where('is_pending', '=', 0);
                    break;
            }
        }

        return $this->hasMany(Org::class);
    }

    public function identities()
    {
        return $this->hasMany(Identity::class);
    }

    public function references()
    {
        return $this->hasMany(Reference::class);
    }
}