<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $indServices = Auth::user()->indService;
        $orgServices = Auth::user()->orgService;
        $navs = $this->navs();

        return view('backend.home', compact('indServices', 'orgServices', 'navs'));
    }

    private function navs()
    {
        $indServices = Auth::user()->indService;
        $orgServices = Auth::user()->orgService;
        $navs = [];

        foreach ($indServices as $indService) {
            array_push($navs, ['url' => route('backend.ind-service.profile', $indService->id), 'text' => $indService->id]);
        }

        foreach ($orgServices as $orgService) {
            array_push($navs, ['url' => route('backend.org-service.profile', $orgService->id), 'text' => $orgService->id]);
        }

        return $navs;
    }
}
