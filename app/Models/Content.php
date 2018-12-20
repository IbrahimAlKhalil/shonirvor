<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['data'];

    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }
}
