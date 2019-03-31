<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    protected $fillable = [
        'name', 'mobile', 'password', 'verification_token'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['dob'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('validate', function (Builder $builder) {
            $builder->whereNull('verification_token');
        });
    }


    /*********************/
    /***** Relations *****/
    /*********************/

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

    public function referPackage()
    {
        return $this->hasOne(UserReferPackage::class);
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function earns()
    {
        return $this->hasMany(Expense::class);
    }

    public function paymentReceiveMethod()
    {
        return $this->hasOne(UserPaymentReceiveMethod::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function sentChatMessages() {
        return $this->morphMany(ChatMessage::class, 'fromable');
    }

    public function receivedChatMessages() {
        return $this->morphMany(ChatMessage::class, 'toable');
    }
}
