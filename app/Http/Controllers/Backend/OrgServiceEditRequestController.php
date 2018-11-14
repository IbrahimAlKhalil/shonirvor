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

    public function show(ServiceEdit $application)
    {
        $application->load([
            'serviceEditable' => function ($query) {
                $query->with('user');
            }
        ]);

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

        $user = $application->serviceEditable->user;
        $data = $application->data;
        $division = Division::find($data['division']);
        $district = District::find($data['district']);
        $thana = Thana::find($data['thana']);
        $union = Union::find($data['union']);
        $village = Village::find($data['village']);

        return view('backend.request.ind-service-edit.show', compact('application', 'user', 'data', 'division', 'district', 'thana', 'union', 'village', 'subCategories', 'workMethodNames', 'subCategoryRequests', 'workImages'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $application = ServiceEdit::findOrFail($request->post('application-id'));
        $ind = $application->serviceEditable;
        $data = $application->data;

        $ind->mobile = $data['mobile'];
        $ind->email = $data['email'];
        $ind->facebook = $data['facebook'];
        $ind->website = $data['website'];
        $ind->address = $data['address'];
        $ind->division_id = $data['division'];
        $ind->district_id = $data['district'];
        $ind->thana_id = $data['thana'];
        $ind->village_id = $data['village'];
        if (isset($data['cover-photo'])) {
            $ind->cover_photo = $data['cover-photo'];
        }
        $ind->save();

        foreach ($data['sub-categories'] as $subcategory) {
            foreach ($subcategory['work-methods'] as $key => $workmethod) {
                $method = IndWorkMethod::where('ind_id', $ind->id)->where('sub_category_id', $subcategory['id'])->where('work_method_id', $key + 1)->first();
                if (!$method) continue;
                if ($key == 3) {
                    if ($workmethod['rate'] != 'negotiable') {
                        $method->delete();
                    }
                    continue;
                }

                $method->rate = $workmethod['rate'];
                $method->save();
            }
        }

        $application->delete();
        DB::commit();

        return redirect(route('backend.request.ind-service-edit.index'))->with('success', 'অনুরোধটি সফলভাবে গৃহীত হয়েছে!');
    }

    public function destroy(ServiceEdit $application)
    {
        DB::beginTransaction();

        // TODO:: Don't forget to delete documents/images

        $application->delete();

        DB::commit();

        return redirect(route('backend.request.ind-service-edit.index'))->with('success', 'অনুরোধটি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
