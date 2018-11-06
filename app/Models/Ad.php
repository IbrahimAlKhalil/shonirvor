<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{

    protected $dates = ['expire'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->morphMany(Income::class, 'incomeable');
    }

    public function renewAsset()
    {
        return $this->hasOne(AdRenewAsset::class);
    }

    public function scopeOnlyApproved($query)
    {
        $query->where('expire', '>', Carbon::now());
    }

    public function scopeOnlyPending($query)
    {
        $query->where('expire', null);
    }
}
