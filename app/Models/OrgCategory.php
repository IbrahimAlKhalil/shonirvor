<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgCategory extends Model
{
    public function subCategories()
    {
        return $this->hasMany(OrgSubCategory::class, 'org_category_id');
    }
}
