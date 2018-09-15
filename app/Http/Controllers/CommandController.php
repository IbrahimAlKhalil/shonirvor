<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class CommandController extends Controller
{
    public function storage()
    {
        Artisan::call('storage:link');
        return redirect(route('frontend.home'));
    }
}
