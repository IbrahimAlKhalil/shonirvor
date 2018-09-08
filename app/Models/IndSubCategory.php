<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndSubCategory extends Model
{
    public function parentCategory() {
        return $this->belongsTo(IndCategory::class, 'ind_category_id');
    }
}
