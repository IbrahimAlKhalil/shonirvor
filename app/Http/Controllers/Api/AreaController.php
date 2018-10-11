<?php

namespace App\Http\Controllers\Api;

use App\Models\Village;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Division;

class AreaController extends Controller
{

    public function divisions()
    {
        $result = null;
        $id = theId();

        if ($id) {
            return Division::find($id);
        }

        return Division::all();
    }

    public function districts(Request $request)
    {
        $result = null;
        $id = theId();

        if ($id) {
            return District::find($id)->select('id', 'bn_name as name');
        }


        $hasPaginate = $request->has('paginate');
        $paginate = $request->get('paginate');

        if ($request->has('division')) {
            $divisionId = $request->get('division');
            switch ($hasPaginate) {
                case true:
                    $result = Division::find($divisionId)
                        ->districts()
                        ->select('id', 'bn_name as name')
                        ->paginate($paginate);
                    break;
                case false:
                    $result = Division::find($divisionId)
                        ->districts()
                        ->select('id', 'bn_name as name')
                        ->get();
            }

            return $result;
        }

        if ($request->has('paginate')) {
            return District::select('id', 'bn_name as name')->paginate($paginate);
        }

        return District::select('id', 'bn_name as name')->get();
    }


    public function thanas(Request $request)
    {
        $result = null;
        $id = theId();

        if ($id) {
            return Thana::find($id)->select('id', 'bn_name as name');
        }


        $hasPaginate = $request->has('paginate');
        $hasDistrict = $request->has('district');
        $paginate = $request->get('paginate');
        $districtId = $request->get('district');
        $setDistrict = function () use ($hasPaginate, $paginate, $districtId) {
            $result = null;
            switch ($hasPaginate) {
                case true:
                    $result = District::find($districtId)
                        ->thanas()
                        ->select('id', 'bn_name as name')
                        ->paginate($paginate)->all();
                    break;
                case false:
                    $result = District::find($districtId)
                        ->thanas()
                        ->select('id', 'bn_name as name')
                        ->get();
            }
            return $result;
        };

        if ($request->has('division')) {
            $divisionId = $request->get('division');
            switch ($hasDistrict) {
                case true:
                    $result = call_user_func($setDistrict);
                    break;
                case false:
                    if ($hasPaginate) {
                        $result = Division::find($divisionId)
                            ->thanas()
                            ->select('id', 'bn_name as name')
                            ->paginate($paginate)->all();
                    } else {
                        $result = Division::find($divisionId)->thanas()
                            ->select('id', 'bn_name as name')
                            ->get();
                    }
            }

            return $result;
        }

        if ($hasDistrict) {
            return call_user_func($setDistrict);
        }

        if ($hasPaginate) {
            return Thana::select('id', 'bn_name as name')->paginate($paginate)->all();
        }

        return Thana::select('id', 'bn_name as name')->get();
    }


    public function unions(Request $request)
    {
        $result = null;
        $id = theId();

        if ($id) {
            return Union::find($id)->select('id', 'bn_name as name');
        }


        $hasPaginate = $request->has('paginate');
        $hasDistrict = $request->has('district');
        $hasThana = $request->has('thana');
        $paginate = $request->get('paginate');
        $districtId = $request->get('district');
        $thanaId = $request->get('thana');
        $setThana = function () use ($hasPaginate, $paginate, $thanaId, $hasThana) {
            $result = null;
            switch ($hasPaginate) {
                case true:
                    $result = Thana::find($thanaId)
                        ->unions()
                        ->select('id', 'bn_name as name')
                        ->paginate($paginate);
                    break;
                case false:
                    $result = Thana::find($thanaId)->unions()
                        ->select('id', 'bn_name as name')
                        ->get();
            }
            return $result;
        };

        $setDistrict = function () use ($hasPaginate, $paginate, $hasDistrict, $hasThana, $setThana, $districtId) {
            $result = null;
            switch ($hasThana) {
                case true:
                    $result = call_user_func($setThana);
                    break;
                case false:
                    if ($hasPaginate) {
                        $result = District::find($districtId)
                            ->unions()
                            ->select('id', 'bn_name as name')
                            ->paginate($paginate);
                    } else {
                        $result = District::find($districtId)->unions;
                    }
            }
            return $result;
        };

        if ($request->has('division')) {
            $divisionId = $request->get('division');
            if ($hasDistrict) {
                $result = call_user_func($setDistrict);
            } else {
                switch ($hasPaginate) {
                    case true:
                        $result = Division::find($divisionId)
                            ->unions()
                            ->select('id', 'bn_name as name')
                            ->paginate($paginate);
                        break;
                    case false:
                        $result = Division::find($divisionId)->unions()
                            ->select('id', 'bn_name as name')
                            ->get();
                }
            }

            return $result;
        }

        if ($hasDistrict) {
            return call_user_func($setDistrict);
        }

        if ($hasThana) {
            return call_user_func($setThana);
        }

        if ($hasPaginate) {
            return Union::select('id', 'bn_name as name')->paginate($paginate)->all();
        }

        return Union::select('id', 'bn_name as name')->get();
    }

    public function villages(Request $request)
    {
        return Village::select('id', 'bn_name as name')->where('union_id', $request->get('union'))->get();
    }
}
