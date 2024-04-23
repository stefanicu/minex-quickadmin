<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Brand;

class ContactController extends Controller
{
    public function index()
    {
        $applications = Application::leftJoin('application_translations','applications.id','=','application_translations.application_id' )
            ->select('name','slug')
            ->where('name','!=','')
            ->where('locale','=',app()->getLocale())
            ->where('applications.online','=',1)
            ->where('application_translations.online','=',1)
            ->orderBy('name','asc')
            ->get();



        return view('brands', compact('applications','brands'));
    }
}
