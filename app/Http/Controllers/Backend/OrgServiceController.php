<?php

namespace App\Http\Controllers\Backend;

use App\Models\OrgService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrgServiceController extends Controller
{

    public function index()
    {
        $orgServices = OrgService::paginate(15);
        return view('backend.org-service.index', compact('orgServices'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show(OrgService $orgService)
    {
        return view('backend.org-service.show', compact('orgService'));
    }

}
