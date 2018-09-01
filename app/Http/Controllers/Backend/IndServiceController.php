<?php

namespace App\Http\Controllers\Backend;

use App\Models\IndService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndServiceController extends Controller
{

    public function index()
    {
        $indServices = IndService::paginate(15);

        return view('backend.ind-service.index', compact('indServices'));
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(IndService $indService)
    {
        return view('backend.ind-service.show', compact('indService'));
    }
}
