<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Ind;
use App\Models\ServiceEdit;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class IndServiceEditController extends Controller
{

    public function index()
    {
        $navs = $this->navs();
        $serviceEdits = ServiceEdit::orderBy('updated_at', 'DSC')->paginate(15);
        return view('backend.ind-service-edit.index', compact('serviceEdits', 'navs'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $serviceEdit = ServiceEdit::find($request->post('id'));
        $data = json_decode($serviceEdit->data, true);
        $ind = Ind::find($serviceEdit->service_editable_id);
        $user = User::find($ind->user_id);

        $ind->mobile = $data['mobile'];
        $ind->email = $data['email'];
        $ind->website = $data['website'];
        $ind->facebook = $data['facebook'];
        $ind->address = $data['address'];
        $ind->experience_certificate = $data['experience-certificate'];

        $user->qualification = $data['qualification'];
        $user->nid = $data['nid'];

        if (array_key_exists('sub-categories', $data)) {
            $ind->subCategories()->detach();
            $subCategories = SubCategory::whereIn('id', $data['sub-categories'])->get();
            $ind->subCategories()->saveMany($subCategories);
        }

        if ($request->has('sub-categories')) {
            foreach ($request->post('sub-categories') as $subCategory) {
                $newSubCategory = SubCategory::find($subCategory['id']);
                $newSubCategory->update(['name' => $subCategory['name'], 'is_confirmed' => 1]);
                $ind->subCategories()->save($newSubCategory);
            }
        }

        // District, thana, union
        $ind->division_id = $data['division'];
        $ind->district_id = $data['district'];

        if ($request->has('thana')) {
            $thana = Thana::find($request->post('thana')['id']);
            $thana->name = $request->post('name');
            $thana->is_pending = 0;
            $thana->save();
        } else {
            $thana = Thana::find($data['thana']);
        }
        $ind->thana_id = $thana->id;

        if ($request->has('union')) {
            $union = Union::find($request->post('union')['id']);
            $union->name = $request->post('name');
            $union->is_pending = 0;
            $union->save();
        } else {
            $union = Union::find($data['union']);
        }
        $ind->union_id = $union->id;

        // work methods
        $workMethods = [];
        $ind->workMethods()->detach();
        foreach ($data['work-methods'] as $workMethod) {
            array_key_exists('id', $workMethod) && array_push($workMethods, [
                'work_method_id' => $workMethod['id'],
                'ind_id' => $ind->id,
                'rate' => $workMethod['rate'],
                'is_negotiable' => array_key_exists('is-negotiable', $workMethod) && $workMethod['is-negotiable'] == 'on'
            ]);
        }
        DB::table('ind_work_method')->insert($workMethods);

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
        $ind->workImages()->createMany($images);

        $ind->save();
        $user->save();

        $serviceEdit->delete();

        DB::commit();

        return redirect(route('individual-service-edit.index'))->with('success', 'সার্ভিস প্রভাইডারের প্রোফাইল আপডেট হয়েছে');
    }

    public function show(ServiceEdit $serviceEdit)
    {
        $ind = $serviceEdit->serviceEditable;
        $data = json_decode($serviceEdit->data, true);

        $navs = $this->navs();

        return view('backend.ind-service-edit.show', compact('serviceEdit', 'data', 'ind', 'navs'));
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

        return redirect(route('individual-service-edit.index'))->with('success', 'সার্ভিস প্রভাইডারের প্রোফাইল আপডেট প্রত্যাখ্যান করা হয়েছে');
    }

    private function navs()
    {
        return [
            ['url' => route('individual-service.index'), 'text' => 'সকল সার্ভিস প্রভাইডার'],
            ['url' => route('individual-service-request.index'), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('individual-service.disabled'), 'text' => 'বাতিল সার্ভিস প্রভাইডার'],
            ['url' => route('individual-service-edit.index'), 'text' => 'প্রোফাইল এডিট রিকোয়েস্ট']
        ];
    }
}
