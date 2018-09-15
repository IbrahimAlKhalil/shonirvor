<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkMethod extends Model
{
    public function  inds() {
        return $this->belongsToMany(Ind::class);
    }
}
