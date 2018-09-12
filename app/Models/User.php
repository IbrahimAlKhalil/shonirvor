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

    public function indService()
    {
        return $this->hasMany(IndService::class);
    }

    public function pendingIndService()
    {
        return $this->hasOne(PendingIndService::class);
    }

    public function orgService()
    {
        return $this->hasMany(OrgService::class);
    }

    public function pendingOrgService()
    {
        return $this->hasMany(PendingOrgService::class);
    }
}