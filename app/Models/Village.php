<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use App\Models\Division;
use App\Models\Thana;
use App\Models\Union;
use Znck\Eloquent\Traits\BelongsToThrough;

class Village extends Model
{
    use BelongsToThrough;

    public $timestamps = false;

    public function union()
    {
        return $this->belongsTo(Union::class);
    }

    public function thana()
    {
        return $this->belongsToThrough(Thana::class, Union::class);
    }

    public function district()
    {
        return $this->belongsToThrough(District::class, [Thana::class, Union::class]);
    }

    public function division()
    {
        return $this->belongsToThrough(Division::class, [District::class, Thana::class, Union::class]);
    }
}
