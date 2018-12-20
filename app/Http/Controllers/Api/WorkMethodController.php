<?php

namespace App\Http\Controllers\Api;

use App\Models\WorkMethod;
use App\Http\Controllers\Controller;

class WorkMethodController extends Controller
{
    public function __invoke()
    {
        $data = WorkMethod::get(['name', 'id']);

        return response($data);
    }
}
