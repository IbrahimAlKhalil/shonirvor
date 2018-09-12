<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mobile', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function dealer()
    {
        return $this->hasOne(Dealer::class);
    }

    public function pendingDealer()
    {
        return $this->hasOne(PendingDealer::class);
    }

    public function indServices()
    {
        return $this->hasMany(IndService::class);
    }

    public function pendingIndServices()
    {
        return $this->hasMany(PendingIndService::class);
    }

    public function orgServices()
    {
        return $this->hasMany(OrgService::class);
    }

    public function pendingOrgServices()
    {
        return $this->hasMany(PendingOrgService::class);
    }
}
