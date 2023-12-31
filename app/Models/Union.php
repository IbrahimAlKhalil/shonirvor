<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;

class Union extends Model
{
    use BelongsToThrough;

    public $timestamps = false;

    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    public function thana()
    {
        return $this->belongsTo(Thana::class);
    }

    public function district()
    {
        return $this->belongsToThrough(District::class, Thana::class);
    }

    public function division()
    {
        return $this->belongsToThrough(Division::class, [District::class, Thana::class]);
    }
}