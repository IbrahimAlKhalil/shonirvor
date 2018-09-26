<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $inds = Auth::user()->inds()->withTrashed()->get();
        $orgs = Auth::user()->orgs()->withTrashed()->get();
        $navs = $this->navs();

        return view('backend.home', compact('inds', 'orgs', 'navs'));
    }

    private function navs()
    {
        $inds = Auth::user()->inds()->withTrashed()->get();
        $orgs = Auth::user()->orgs()->withTrashed()->get();
        $navs = [];

        foreach ($inds as $ind) {
            array_push($navs, ['url' => route('profile.backend.individual-service.show', $ind->id), 'text' => $ind->category->name, 'after' => '&nbsp;<span class="badge badge-primary pull-right">ব্যাক্তিগত</span>']);
        }

        foreach ($orgs as $org) {
            array_push($navs, ['url' => route('profile.backend.organization-service.show', $org->id), 'text' => $org->name, 'after' => '&nbsp;<span class="badge badge-primary pull-right">প্রাতিষ্ঠানিক</span>']);
        }

        return $navs;
    }
}
