<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\FrontPage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
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

        $hero = FrontPage::leftJoin('front_page_translations','front_pages.id','=','front_page_translations.front_page_id' )
            ->select('name','first_text','second_text')
            ->where('locale','=',app()->getLocale())
            ->where('front_page_id','=',1)
            ->first();

        $integrated_solutions = FrontPage::leftJoin('front_page_translations','front_pages.id','=','front_page_translations.front_page_id' )
            ->select('name','first_text','second_text','quote','button')
            ->where('locale','=',app()->getLocale())
            ->where('front_page_id','=',2)
            ->first();

        $consultancy = FrontPage::leftJoin('front_page_translations','front_pages.id','=','front_page_translations.front_page_id' )
            ->select('name','first_text','quote')
            ->where('locale','=',app()->getLocale())
            ->where('front_page_id','=',3)
        ->first();

        return view('welcome', compact('applications','hero', 'integrated_solutions', 'consultancy'));
    }
}
