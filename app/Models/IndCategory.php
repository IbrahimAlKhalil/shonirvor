<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndCategory extends Model
{
    public function subCategories()
    {
        return $this->hasMany(IndSubCategory::class, 'ind_category_id');
    }

    public function indServices()
    {
        return $this->hasMany(IndService::class);
    }

    public function pendingIndServices()
    {
        return $this->hasMany(PendingIndService::class);
    }
}
