<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndCategory extends Model
{
    public function subCategories() {
        return $this->hasMany(IndSubCategory::class, 'ind_category_id');
    }
}
