<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{
    private $id = false;
    private $type = false;
    private $category = false;

    public function __construct(Request $request)
    {
        if (theId()) {
            $this->id = theId();
        }

        if ($request->filled('type')) {
            $this->type = $request->get('type');
        }

        if ($request->filled('category')) {
            $this->category = $request->get('category');
        }
    }

    public function __invoke()
    {
        if ($this->id) {

            $data = SubCategory::with('category')->find($this->id);

        } elseif ($this->type) {

            if ($this->type == 'ind') {

                $data = SubCategory::onlyInd()
                    ->onlyConfirmed()
                    ->get();

            } elseif ($this->type == 'org') {

                $data = SubCategory::onlyOrg()
                    ->onlyConfirmed()
                    ->get();

            }

        } elseif ($this->category) {

            $data = Category::find($this->category)
                        ->subCategories()
                        ->onlyConfirmed()
                        ->get();

        } else {

            $data = SubCategory::onlyConfirmed()->get();
        }


        return response($data);
    }
}