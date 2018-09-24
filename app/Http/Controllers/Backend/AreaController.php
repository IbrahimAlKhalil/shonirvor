<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Division;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class AreaController extends Controller
{
    public function division()
    {
        $divisions = Division::select(['id', 'bn_name'])->get();
        return view('backend.area.division', compact('divisions'));
    }

    public function district(Division $division)
    {
        $allDivision = Division::select(['id', 'bn_name'])->get();
        $districts = District::select(['id', 'bn_name'])->where('division_id', $division->id)->get();

        return view('backend.area.district', compact('division', 'districts', 'allDivision'));
    }

    public function thana(District $district)
    {
        $allDivision = Division::select(['id', 'bn_name'])->get();
        $allDistrict = District::select(['id', 'bn_name'])->get();
        $thanas = Thana::select(['id', 'bn_name'])->where('district_id', $district->id)->get();

        return view('backend.area.thana', compact('district', 'thanas', 'allDivision', 'allDistrict'));
    }

    public function union(Thana $thana)
    {
        $allDivision = Division::select(['id', 'bn_name'])->get();
        $allDistrict = District::select(['id', 'bn_name'])->get();
        $allThana = Thana::select(['id', 'bn_name'])->get();
        $unions = Union::select(['id', 'bn_name'])->where('thana_id', $thana->id)->get();

        return view('backend.area.union', compact('thana', 'unions', 'allDivision', 'allDistrict', 'allThana'));
    }

    public function storeDivision(Request $request)
    {
        $division = new Division();
        $division->bn_name = $request->input('division');
        $division->save();

        return redirect(route('backend.area.division'))->with('success', 'নতুন বিভাগ যুক্ত হয়েছে।');
    }

    public function storeDistrict($divisionId, Request $request)
    {
        $district = new District();
        $district->division_id = $divisionId;
        $district->bn_name = $request->input('district');
        $district->save();

        return redirect(route('backend.area.district', $divisionId))->with('success', 'নতুন জেলা যুক্ত হয়েছে।');
    }

    public function storeThana($districtId, Request $request)
    {
        $thana = new Thana();
        $thana->district_id = $districtId;
        $thana->bn_name = $request->input('thana');
        $thana->save();

        return redirect(route('backend.area.thana', $districtId))->with('success', 'নতুন থানা যুক্ত হয়েছে।');
    }

    public function storeUnion($thanaId, Request $request)
    {
        $union = new Union();
        $union->thana_id = $thanaId;
        $union->bn_name = $request->input('union');
        $union->save();

        return redirect(route('backend.area.union', $thanaId))->with('success', 'নতুন ইউনিয়ন যুক্ত হয়েছে।');
    }

    public function updateDivision(Division $division, Request $request)
    {
        $oldName = $division->bn_name;

        $division->bn_name = $request->input('bn_name');
        $division->save();

        return back()->with('success', $oldName.' বিভাগটি এডিট হয়েছে।');
    }

    public function updateDistrict(District $district, Request $request)
    {
        $oldName = $district->bn_name;

        $district->division_id = $request->input('division_id');
        $district->bn_name = $request->input('bn_name');
        $district->save();

        return back()->with('success', $oldName.' জেলাটি এডিট হয়েছে।');
    }

    public function updateThana(Thana $thana, Request $request)
    {
        $oldName = $thana->bn_name;

        $thana->district_id = $request->input('district_id');
        $thana->bn_name = $request->input('bn_name');
        $thana->save();

        return back()->with('success', $oldName.' থানাটি এডিট হয়েছে।');
    }

    public function updateUnion(Union $union, Request $request)
    {
        $oldName = $union->bn_name;

        $union->thana_id = $request->input('thana_id');
        $union->bn_name = $request->input('bn_name');
        $union->save();

        return back()->with('success', $oldName.' ইউনিয়নটি এডিট হয়েছে।');
    }

    public function destroyDivision(Division $division)
    {
        $divisionName = $division->bn_name;

        if ($division->districts->isNotEmpty()) {
            return back()->with('error', $divisionName.' বিভাগটির ভিতর জেলা রয়েছে, তাই এই বিভাগটি মুছে ফেলা যাবে না।');
        }
        else
        {
            $division->delete();
            return back()->with('success', $divisionName.' বিভাগটি মুছে ফেলা হয়েছে।');
        }
    }

    public function destroyDistrict(District $district)
    {
        $districtName = $district->bn_name;

        if ($district->thanas->isNotEmpty()) {
            return back()->with('error', $districtName.' জেলাটির ভিতর থানা রয়েছে, তাই এই জেলাটি মুছে ফেলা যাবে না।');
        }
        elseif (DB::table('inds')->where('district_id', $district->id)->exists())
        {
            return back()->with('error', $districtName.' জেলাটির ভিতর বেক্তিগত সার্ভিস প্রভাইডার রয়েছে, তাই এই জেলাটি মুছে ফেলা যাবে না।');
        }
        elseif (DB::table('orgs')->where('district_id', $district->id)->exists())
        {
            return back()->with('error', $districtName.' জেলাটির ভিতর প্রাতিষ্ঠানিক সার্ভিস প্রভাইডার রয়েছে, তাই এই জেলাটি মুছে ফেলা যাবে না।');
        }
        else
        {
            $district->delete();
            return back()->with('success', $districtName.' জেলাটি মুছে ফেলা হয়েছে।');
        }
    }

    public function destroyThana(Thana $thana)
    {
        $thanaName = $thana->bn_name;

        if ($thana->unions->isNotEmpty()) {
            return back()->with('error', $thanaName.' থানাটির ভিতর ইউনিয়ন রয়েছে, তাই এই থানাটি মুছে ফেলা যাবে না।');
        }
        elseif (DB::table('inds')->where('thana_id', $thana->id)->exists())
        {
            return back()->with('error', $thanaName.' থানাটির ভিতর বেক্তিগত সার্ভিস প্রভাইডার রয়েছে, তাই এই থানাটি মুছে ফেলা যাবে না।');
        }
        elseif (DB::table('orgs')->where('thana_id', $thana->id)->exists())
        {
            return back()->with('error', $thanaName.' থানাটির ভিতর প্রাতিষ্ঠানিক সার্ভিস প্রভাইডার রয়েছে, তাই এই থানাটি মুছে ফেলা যাবে না।');
        }
        else
        {
            $thana->delete();
            return back()->with('success', $thanaName.' থানাটি মুছে ফেলা হয়েছে।');
        }
    }

    public function destroyUnion(Union $union)
    {
        $unionName = $union->bn_name;

        if (DB::table('inds')->where('union_id', $union->id)->exists())
        {
            return back()->with('error', $unionName.' ইউনিয়নটির ভিতর বেক্তিগত সার্ভিস প্রভাইডার রয়েছে, তাই এই ইউনিয়নটি মুছে ফেলা যাবে না।');
        }
        elseif (DB::table('orgs')->where('union_id', $union->id)->exists())
        {
            return back()->with('error', $unionName.' ইউনিয়নটির ভিতর প্রাতিষ্ঠানিক সার্ভিস প্রভাইডার রয়েছে, তাই এই ইউনিয়নটি মুছে ফেলা যাবে না।');
        }
        else
        {
            $union->delete();
            return back()->with('success', $unionName.' ইউনিয়নটি মুছে ফেলা হয়েছে।');
        }
    }
}