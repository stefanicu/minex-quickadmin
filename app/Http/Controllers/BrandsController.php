<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Brand;

class BrandsController extends Controller
{
    public function index()
    {
        $applications = Application::all();
        $brands = Brand::select('id','name','slug')->where('online','=',1)->orderBy('name')->get();

        return view('brands', compact('applications','brands'));
    }
}
