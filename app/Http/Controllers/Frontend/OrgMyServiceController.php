<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Org;
use App\Http\Controllers\Controller;

class OrgMyServiceController extends Controller
{
    public function show(Org $service)
    {
        dd($service);
    }
}
