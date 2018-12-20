<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPaymentReceiveMethod extends Model
{
    protected $fillable = ['user_id', 'type', 'number'];
}
