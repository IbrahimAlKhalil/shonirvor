<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\IndWorkMethod;
use App\Models\ServiceEdit;
use App\Models\Slug;
use App\Models\SubCategory;
use App\Models\Thana;
use App\Models\Union;
use App\Models\Village;
use App\Models\WorkImage;
use App\Models\WorkMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IndServiceEditRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

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

    public function show($id)
    {
        $application = ServiceEdit::with([
            'serviceEditable' => function ($query) {
                $query->with('user');
            }
        ])->where('service_editable_type', 'ind')->findOrFail($id);

        $subCategoryArr = array_key_exists("sub-categories", $application->data) ? $application->data['sub-categories'] : [];
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

        $workImages = $workImages = WorkImage::select('id', 'path')->whereIn('id', array_keys(isset($application->data['images']) ? $application->data['images'] : []))->get();

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

        $application = ServiceEdit::where('service_editable_type', 'ind')->findOrFail($request->post('application-id'));
        $ind = $application->serviceEditable;
        $data = $application->data;

        $ind->description = $data['description'];
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

        $slug = $ind->slug;
        if ($data['slug'] != $slug->name) {
            $slug->name = $data['slug'];
            $slug->save();
        }


        // store sub-category edits
        if (array_key_exists('sub-categories', $data)) {
//            $subCategoryIds = array_map(function ($item) {
//                return $item['id'];
//            }, $data['sub-categories']);


            // Delete ind workmethods
            IndWorkMethod::where('ind_id', $ind->id)->delete();

            // Detach sub categories
            $ind->subCategories()->detach();

            $subCategoriables = [];

            foreach ($data['sub-categories'] as $subCategory) {
                array_push($subCategoriables, [
                    'sub_category_id' => $subCategory['id'],
                    'sub_categoriable_id' => $ind->id,
                    'sub_categoriable_type' => 'ind'
                ]);

                $indWorkMethods = [];
                foreach ($subCategory['work-methods'] as $key => $workMethod) {
                    if (3 == $key && $workMethod['rate'] != 'negotiable') continue;

                    array_push($indWorkMethods, [
                        'ind_id' => $ind->id,
                        'work_method_id' => $key + 1,
                        'sub_category_id' => $subCategory['id'],
                        'rate' => $workMethod['rate']
                    ]);
                }

                DB::table('ind_work_method')->insert($indWorkMethods);
            }

            DB::table('sub_categoriables')->insert($subCategoriables);
        }

        // store sub-category requests
        if (isset($data['sub-category-requests'])) {
            foreach ($data['sub-category-requests'] as $datum) {
                $subCategory = new SubCategory;
                $subCategory->category_id = $ind->category_id;
                $subCategory->name = $datum['name'];
                $subCategory->is_confirmed = 1;
                $subCategory->save();

                $workMethods = [];
                foreach ($datum['work-methods'] as $index => $workMethod) {
                    if ($index != 3) {
                        if (!is_null($workMethod['rate'])) {
                            array_push($workMethods, [
                                'ind_id' => $ind->id,
                                'sub_category_id' => $subCategory->id,
                                'work_method_id' => $index + 1,
                                'rate' => $workMethod['rate']
                            ]);
                        }

                        continue;
                    }

                    if ($workMethod['rate'] == 'negotiable') {
                        array_push($workMethods, [
                            'ind_id' => $ind->id,
                            'sub_category_id' => $subCategory->id,
                            'work_method_id' => $index + 1,
                            'rate' => 0
                        ]);
                    }
                }

                DB::table('ind_work_method')->insert($workMethods);

                $ind->subCategories()->attach($subCategory);
            }
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
                    'work_imagable_type' => 'ind',
                    'work_imagable_id' => $ind->id,
                    'path' => $datum['file'],
                    'description' => isset($datum['description']) ? $datum['description'] : null
                ]);
            }
        }
        DB::table('work_images')->insert($newWorkImages);

        $application->delete();
        DB::commit();

        return redirect(route('backend.request.ind-service-edit.index'))->with('success', 'অনুরোধটি সফলভাবে গৃহীত হয়েছে!');
    }

    public function destroy($id)
    {
        $application = ServiceEdit::where('service_editable_type', 'ind')->findOrFail($id);
        $data = $application->data;
        DB::beginTransaction();

        // TODO:: Don't forget to delete documents/images

        $application->delete();

        if (isset($data['cover-photo'])) {
            Storage::delete($data['cover-photo']);
        }

        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                if (isset($image['file'])) {
                    Storage::delete($image->file);
                }
            }
        }

        if(isset($data['new-work-images'])) {
            foreach ($data['new-work-images'] as $image) {
                if (isset($image['file'])) {
                    Storage::delete($image->file);
                }
            }
        }

        DB::commit();

        return redirect(route('backend.request.ind-service-edit.index'))->with('success', 'অনুরোধটি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
