<?php

namespace App\Models;

use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;
use Illuminate\Database\Eloquent\Model;
use Sandofvega\Bdgeocode\Models\District;

class PendingDealer extends Model
{
    public function documents()
    {
        return $this->hasMany(PendingDealerDocument::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function thana()
    {
        return $this->belongsTo(Thana::class);
    }

    public function union()
    {
        return $this->belongsTo(Union::class);
    }
}
