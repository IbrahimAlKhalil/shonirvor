<?php

namespace App\Http\Controllers\Backend;

use App\Models\District;
use App\Models\Division;
use App\Models\OrgAdditionalPrice;
use App\Models\ServiceEdit;
use App\Models\SubCategory;
use App\Models\Thana;
use App\Models\Union;
use App\Models\Village;
use App\Models\WorkImage;
use App\Models\WorkMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrgServiceEditRequestController extends Controller
{
    public function index()
    {
        $applications = ServiceEdit::with([
            'serviceEditable' => function ($query) {
                $query->with('category');
            }
        ])->where('service_editable_type', 'org')->orderBy('updated_at', 'DSC')->paginate(15);
        $navs = [
            ['url' => route('backend.request.org-service-request.index'), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('backend.request.top-service.index') . '?type=3', 'text' => 'টপ সার্ভিস রিকোয়েস্ট'],
            ['url' => route('backend.request.org-service-edit.index'), 'text' => 'এডিট রিকোয়েস্ট']
        ];

        return view('backend.request.org-service-edit.index', compact('applications', 'navs'));
    }

    public function show($id)
    {
        $application = ServiceEdit::where('service_editable_type', 'org')->with('serviceEditable')->findOrFail($id);

        $subCategoryArr = $application->data['sub-categories'];
        $subCategoryIds = array_map(function ($item) {
            return $item['id'];
        }, $subCategoryArr);
        $subCategories = SubCategory::onlyConfirmed()->select('name')->whereIn('id', $subCategoryIds)->get();

        $workImages = WorkImage::select('id', 'path')->whereIn('id', array_keys($application->data['images']))->get();

        $data = $application->data;
        $division = Division::find($data['division']);
        $district = District::find($data['district']);
        $thana = Thana::find($data['thana']);
        $union = Union::find($data['union']);
        $village = Village::find($data['village']);

        return view('backend.request.org-service-edit.show', compact('application', 'data', 'division', 'district', 'thana', 'union', 'village', 'subCategories', 'workImages'));
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        $application = ServiceEdit::with('serviceEditable')->where('service_editable_type', 'org')->findOrFail($request->post('application-id'));
        $org = $application->serviceEditable;
        $data = $application->data;

        $org->mobile = $data['mobile'];
        $org->email = $data['email'];
        $org->facebook = $data['facebook'];
        $org->website = $data['website'];
        $org->address = $data['address'];
        $org->division_id = $data['division'];
        $org->district_id = $data['district'];
        $org->thana_id = $data['thana'];
        $org->village_id = $data['village'];
        $org->slug = $data['slug'];
        if (isset($data['logo'])) {
            $org->cover_photo = $data['logo'];
        }
        if (isset($data['cover-photo'])) {
            $org->cover_photo = $data['cover-photo'];
        }
        $org->save();

        $subCategoryIds = array_map(function ($item) {
            return $item['id'];
        }, $application->data['sub-categories']);

        $org->subCategories()->detach();
        DB::table('org_sub_category_rates')->whereIn('sub_category_id', $subCategoryIds)->where('org_id', $org->id)->delete();


        $subCategories = [];
        $subCategoryRates = [];
        foreach ($data['sub-categories'] as $datum) {
            array_push($subCategoryRates, [
                'org_id' => $org->id,
                'sub_category_id' => $datum['id'],
                'rate' => $datum['rate']
            ]);

            array_push($subCategories, [
                'sub_category_id' => $datum['id'],
                'sub_categoriable_id' => $org->id,
                'sub_categoriable_type' => 'org'
            ]);
        }

        if (isset($data['sub-category-requests'])) {
            foreach ($request->post('sub-category-requests') as $datum) {
                $newSubCategory = new SubCategory;
                $newSubCategory->category_id = $org->category_id;
                $newSubCategory->name = $datum['name'];
                $newSubCategory->is_confirmed = 1;
                $newSubCategory->save();

                array_push($subCategoryRates, [
                    'org_id' => $org->id,
                    'sub_category_id' => $newSubCategory->id,
                    'rate' => $datum['rate']
                ]);

                array_push($subCategories, [
                    'sub_category_id' => $newSubCategory->id,
                    'sub_categoriable_id' => $org->id,
                    'sub_categoriable_type' => 'org'
                ]);
            }
            DB::table('sub_categoriables')->insert($subCategories);
        }

        DB::table('org_sub_category_rates')->insert($subCategoryRates);

        foreach ($data['kaj'] as $datum) {
            DB::table('org_additional_prices')->where('id', $datum['id'])->update([
                'name' => $datum['name'],
                'info' => $datum['info'],
            ]);
        }

        if (isset($data['kaj-requests'])) {
            $kajRequests = [];
            foreach ($data['kaj-requests'] as $datum) {
                array_push($kajRequests, [
                    'org_id' => $org->id,
                    'name' => $datum['name'],
                    'info' => $datum['info']
                ]);
            }
            DB::table('org_additional_prices')->insert($kajRequests);
        }

        if (isset($data['images'])) {
            foreach ($data['images'] as $id => $datum) {
                $image = WorkImage::find($id);
                $image->description = $datum['description'];
                if (isset($datum['file'])) {
                    $image->path = $datum['file'];
                }
                $image->save();
            }
        }

        $newWorkImages = [];
        if (isset($data['new-work-images'])) {
            foreach ($data['new-work-images'] as $id => $datum) {
                array_push($newWorkImages, [
                    'work_imagable_type' => 'org',
                    'work_imagable_id' => $org->id,
                    'path' => $datum['file'],
                    'description' => isset($datum['description']) ? $datum['description'] : null
                ]);
            }
        }
        DB::table('work_images')->insert($newWorkImages);

        $application->delete();
        DB::commit();

        return redirect(route('backend.request.org-service-edit.index'))->with('success', 'অনুরোধটি সফলভাবে গৃহীত হয়েছে!');
    }

    public function destroy($id)
    {
        $application = ServiceEdit::where('service_editable_type', 'org')->findOrFail($id);

        DB::beginTransaction();

        // TODO:: Don't forget to delete documents/images

        $application->delete();

        DB::commit();

        return redirect(route('backend.request.org-service-edit.index'))->with('success', 'অনুরোধটি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
