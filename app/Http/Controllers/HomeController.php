<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\FrontPage;

class HomeController extends Controller
{
    public function index()
    {
        $applications = Application::all();

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

        $maintenance = FrontPage::leftJoin('front_page_translations','front_pages.id','=','front_page_translations.front_page_id' )
            ->select('name','first_text','second_text','quote', 'button')
            ->where('locale','=',app()->getLocale())
            ->where('front_page_id','=',4)
            ->first();

        $references = FrontPage::leftJoin('front_page_translations','front_pages.id','=','front_page_translations.front_page_id' )
            ->select('name', 'button')
            ->where('locale','=',app()->getLocale())
            ->where('front_page_id','=',5)
            ->first();

        $about_us = FrontPage::leftJoin('front_page_translations','front_pages.id','=','front_page_translations.front_page_id' )
            ->select('name','first_text','second_text')
            ->where('locale','=',app()->getLocale())
            ->where('front_page_id','=',6)
            ->first();

        $contact_us = FrontPage::leftJoin('front_page_translations','front_pages.id','=','front_page_translations.front_page_id' )
            ->select('name','first_text','button')
            ->where('locale','=',app()->getLocale())
            ->where('front_page_id','=',7)
            ->first();

        return view('welcome', compact( 'applications','hero', 'integrated_solutions', 'consultancy', 'maintenance', 'references', 'about_us', 'contact_us'));
    }
}
