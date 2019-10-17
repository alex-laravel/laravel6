<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.index');
    }
}
