<?php

namespace App\Http\Controllers\Backend;

use App\Models\District;
use App\Models\Division;
use App\Models\ServiceEdit;
use App\Models\SubCategory;
use App\Models\Thana;
use App\Models\Union;
use App\Models\Village;
use App\Models\WorkImages;
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

        return view('backend.request.ind-service-edit.index', compact('applications', 'navs'));
    }

    public function show($id)
    {
        $application = ServiceEdit::where('service_editable_type', 'org')->with('serviceEditable')->findOrFail($id);

        $subCategoryArr = $application->data['sub-categories'];
        $subCategoryIds = array_map(function ($item) {
            return $item['id'];
        }, $subCategoryArr);
        $workMethodNames = WorkMethod::select('name')->get();
        $subCategoryCollection = SubCategory::onlyConfirmed()->select('name')->whereIn('id', $subCategoryIds)->get();
        $subCategories = [];


        foreach ($subCategoryCollection as $i => $subCategory) {
            $item = [];
            foreach ($workMethodNames as $c => $methodName) {
                if (isset($subCategoryArr[$i]['work-methods'][$c]['rate'])) {
                    $item[$methodName->name] = $subCategoryArr[$i]['work-methods'][$c]['rate'];
                    continue;
                }

                $item[$methodName->name] = '';
            }
            $subCategories[$subCategory->name] = $item;
        }

        $subCategoryRequests = [];

        if (isset($application->data['sub-category-requests'])) {
            foreach ($application->data['sub-category-requests'] as $i => $subCategory) {
                $item = [];
                foreach ($workMethodNames as $c => $methodName) {
                    if (isset($subCategory['work-methods'][$c]['rate'])) {
                        $item[$methodName->name] = $subCategory['work-methods'][$c]['rate'];
                        continue;
                    }

                    $item[$methodName->name] = '';
                }

                $subCategoryRequests[$subCategory['name']] = $item;
            }
        }

        $workImages = WorkImages::select('id', 'path')->whereIn('id', array_keys($application->data['images']))->get();

        $data = $application->data;
        $division = Division::find($data['division']);
        $district = District::find($data['district']);
        $thana = Thana::find($data['thana']);
        $union = Union::find($data['union']);
        $village = Village::find($data['village']);

        return view('backend.request.org-service-edit.show', compact('application', 'data', 'division', 'district', 'thana', 'union', 'village', 'subCategories', 'workMethodNames', 'subCategoryRequests', 'workImages'));
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
        if (isset($data['logo'])) {
            $org->cover_photo = $data['logo'];
        }
        if (isset($data['cover-photo'])) {
            $org->cover_photo = $data['cover-photo'];
        }
        $org->save();

        foreach ($data['sub-categories'] as $datum) {
            DB::table('org_sub_category_rates')->where('org_id', $org->id)->where('sub_category_id', $datum['id'])->update([
                'name'
            ]);
        }

        $application->delete();
        DB::commit();

        return redirect(route('backend.request.ind-service-edit.index'))->with('success', 'অনুরোধটি সফলভাবে গৃহীত হয়েছে!');
    }

    public function destroy($id)
    {
        $application = ServiceEdit::where('service_editable_type', 'org')->findOrFail($id);

        DB::beginTransaction();

        // TODO:: Don't forget to delete documents/images

        $application->delete();

        DB::commit();

        return redirect(route('backend.request.ind-service-edit.index'))->with('success', 'অনুরোধটি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
