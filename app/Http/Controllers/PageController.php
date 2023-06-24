<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function orderPage()
    {
        return view('pages.order');
    }

    public function productPage()
    {
        return view('pages.product');
    }
}
