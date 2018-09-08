<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgSubCategory extends Model
{
    public function parentCategory()
    {
        return $this->belongsTo(OrgCategory::class, 'org_category_id');
    }
}
