<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    public function incomeable()
    {
        return $this->morphTo();
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
