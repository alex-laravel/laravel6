<?php

namespace App\Http\Controllers\Backend;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        return view('backend.index');
    }
}
