<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        return view('frontend.index');
    }
}
