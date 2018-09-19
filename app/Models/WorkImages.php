<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkImages extends Model
{
    protected $fillable = ['path'];

    public function inds()
    {
        return $this->morphedByMany(Ind::class, 'sub_categoriable');
    }

    public function orgs()
    {
        return $this->morphedByMany(Ind::class, 'sub_categoriable');
    }
}
