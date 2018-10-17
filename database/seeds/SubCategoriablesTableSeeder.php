<?php

use App\Models\Ind;
use App\Models\Org;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SubCategoriablesTableSeeder extends Seeder
{

    public function run()
    {
        $this->seed(Ind::class, 'ind');
        $this->seed(Org::class, 'org');
    }

    /**
     * @param $class Model|string
     * @param $subCategoriableType string
     **/
    private function seed($class, $subCategoriableType)
    {
        $class::all()->each(function ($service) use ($subCategoriableType) {
            $subCategoryIds = $service->category->subCategories()->pluck('id')->toArray();
            $subCategoryIds = randomElements($subCategoryIds, mt_rand(1, count($subCategoryIds)));
            $subCategoriables = [];

            foreach ($subCategoryIds as $subCategoryId) {
                array_push($subCategoriables, [
                    'sub_category_id' => $subCategoryId,
                    'sub_categoriable_id' => $service->id,
                    'sub_categoriable_type' => $subCategoriableType
                ]);
            }
            DB::table('sub_categoriables')->insert($subCategoriables);
        });
    }
}
