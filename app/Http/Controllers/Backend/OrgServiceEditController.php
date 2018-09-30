<?php

namespace App\Http\Controllers\Backend;

use App\Models\Org;
use App\Models\ServiceEdit;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class OrgServiceEditController extends Controller
{
    public function index()
    {
        $navs = $this->navs();
        $serviceEdits = ServiceEdit::orderBy('updated_at', 'DSC')->paginate(15);
        return view('backend.org-service-edit.index', compact('serviceEdits', 'navs'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $serviceEdit = ServiceEdit::find($request->post('id'));
        $data = json_decode($serviceEdit->data, true);
        $org = Org::find($serviceEdit->service_editable_id);
        $user = User::find($org->user_id);

        $org->mobile = $data['mobile'];
        $org->email = $data['email'];
        $org->website = $data['website'];
        $org->facebook = $data['facebook'];
        $org->address = $data['address'];
        $org->trade_license = $data['trade-license'];
        $org->logo = $data['logo'];

        $user->nid = $data['nid'];

        if (array_key_exists('sub-categories', $data)) {
            $org->subCategories()->detach();
            $subCategories = SubCategory::whereIn('id', $data['sub-categories'])->get();
            $org->subCategories()->saveMany($subCategories);
        }

        if ($request->has('sub-categories')) {
            foreach ($request->post('sub-categories') as $subCategory) {
                $newSubCategory = SubCategory::find($subCategory['id']);
                $newSubCategory->update(['name' => $subCategory['name'], 'is_confirmed' => 1]);
                $org->subCategories()->save($newSubCategory);
            }
        }

        // District, thana, union
        $org->division_id = $data['division'];
        $org->district_id = $data['district'];

        if ($request->has('thana')) {
            $thana = Thana::find($request->post('thana')['id']);
            $thana->name = $request->post('name');
            $thana->is_pending = 0;
            $thana->save();
        } else {
            $thana = Thana::find($data['thana']);
        }
        $org->thana_id = $thana->id;

        if ($request->has('union')) {
            $union = Union::find($request->post('union')['id']);
            $union->name = $request->post('name');
            $union->is_pending = 0;
            $union->save();
        } else {
            $union = Union::find($data['union']);
        }
        $org->union_id = $union->id;

        // identities
        $identities = [];
        foreach ($data['identities'] as $identity) {
            array_push($identities, ['path' => $identity, 'user_id' => $user->id]);
        }
        DB::table('identities')->insert($identities);

        // work images
        $images = [];
        foreach ($data['images'] as $image) {
            array_push($images, ['path' => $image]);
        }
        $org->workImages()->createMany($images);

        $org->save();
        $user->save();

        $serviceEdit->delete();

        DB::commit();

        return redirect(route('organization-service-edit.index'))->with('success', 'সার্ভিস প্রভাইডারের প্রোফাইল আপডেট হয়েছে');
    }

    public function show(ServiceEdit $serviceEdit)
    {
        $org = $serviceEdit->serviceEditable;
        $data = json_decode($serviceEdit->data, true);

        dd($data);

        $navs = $this->navs();
        $visitor['today'] = DB::table('org_visitor_counts')->where('org_id', $org->id)->whereDate('created_at', date('Y-m-d'))->sum('how_much');
        $visitor['thisMonth'] = DB::table('org_visitor_counts')->where('org_id', $org->id)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->sum('how_much');
        $visitor['thisYear'] = DB::table('org_visitor_counts')->where('org_id', $org->id)->whereYear('created_at', date('Y'))->sum('how_much');

        return view('backend.org-service-edit.show', compact('serviceEdit', 'data', 'org', 'navs', 'visitor'));
    }

    public function destroy(ServiceEdit $serviceEdit)
    {
        DB::beginTransaction();

        $data = json_decode($serviceEdit->data, true);
        foreach ($data['identities'] as $identity) {
            Storage::delete($identity);
        }
        foreach ($data['images'] as $image) {
            Storage::delete($image);
        }
        if (array_key_exists('sub-category-requests', $data)) {
            SubCategory::whereIn('id', $data['sub-categories'])->delete();
        }
        if (array_key_exists('thana-request', $data)) {
            Thana::find($data['thana-request'])->delete();
        }

        if (array_key_exists('union-request', $data)) {
            Union::find($data['union-request'])->delete();
        }

        $serviceEdit->delete();

        DB::commit();

        return redirect(route('organization-service-edit.index'))->with('success', 'সার্ভিস প্রভাইডারের প্রোফাইল আপডেট প্রত্যাখ্যান করা হয়েছে');
    }

    private function navs()
    {
        return [
            ['url' => route('organization-service.index'), 'text' => 'সকল সার্ভিস প্রভাইডার'],
            ['url' => route('organization-service-request.index'), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('organization-service.disabled'), 'text' => 'বাতিল সার্ভিস প্রভাইডার'],
            ['url' => route('organization-service-edit.index'), 'text' => 'প্রোফাইল এডিট রিকোয়েস্ট']
        ];
    }
}
