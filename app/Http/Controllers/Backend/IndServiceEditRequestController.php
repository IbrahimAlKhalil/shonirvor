<?php

namespace App\Http\Controllers\Backend;

use App\Models\District;
use App\Models\Division;
use App\Models\Ind;
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

class IndServiceEditRequestController extends Controller
{
    public function index()
    {
        $applications = ServiceEdit::with([
            'serviceEditable' => function ($query) {
                $query->with('category');
            }
        ])->where('service_editable_type', 'ind')->orderBy('updated_at', 'DSC')->paginate(15);
        $navs = [
            ['url' => route('backend.request.ind-service-request.index'), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('backend.request.top-service.index') . '?type=3', 'text' => 'টপ সার্ভিস রিকোয়েস্ট'],
            ['url' => route('backend.request.ind-service-edit.index'), 'text' => 'এডিট রিকোয়েস্ট']
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

    public function update(Request $request, ServiceEdit $application)
    {

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
