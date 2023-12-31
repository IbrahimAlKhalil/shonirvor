<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function categories(Request $request)
    {
        $result = null;
        $id = false;

        if (array_key_exists('id', request()->route()->parameters)) {
            $this->id = request()->route()->parameters['id'];
        }

        if ($id) {
            return Category::find($id);
        }

        $hasPaginate = $request->has('paginate');
        $hasServiceType = $request->has('type');
        $hasState = $request->has('state');
        $paginate = $request->get('paginate');
        $serviceType = $request->get('type');
        $state = $request->get('state') == 'pending' ? 0 : 1;
        $getWithState = function ($serviceType) use ($hasPaginate, $paginate, $state) {
            $result = null;
            if ($hasPaginate) {
                $result = Category::getAll($serviceType, $state)
                    ->paginate($paginate)
                    ->all();
            } else {
                $result = Category::getAll($serviceType, $state)->get();
            }
            return $result;
        };

        $getWithoutState = function ($serviceType) use ($hasPaginate, $paginate) {
            $result = null;
            if ($hasPaginate) {
                $result = Category::getAll($serviceType)
                    ->paginate($paginate)
                    ->all();
            } else {
                $result = Category::getAll($serviceType)->get();
            }
            return $result;
        };

        if ($hasServiceType) {
            switch ($hasState) {
                case true:
                    $result = call_user_func($getWithState, $serviceType);
                    break;
                case false:
                    $result = call_user_func($getWithoutState, $serviceType);
            }

            return $result;
        }

        if ($hasState) {
            return call_user_func($getWithState, 'all');
        }

        if ($hasPaginate) {
            return Category::paginate($paginate)->all();
        }

        return Category::all();
    }
}
