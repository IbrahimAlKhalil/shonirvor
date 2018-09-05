<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingOrgServiceImage extends Model
{
    public function registration()
    {
        return $this->belongsTo(OrgService::class);
    }
}
