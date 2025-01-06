<?php

namespace App\Http\Controllers;

use App\Models\FrontPage;
use App\Models\Industry;
use App\Models\Reference;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $hero = FrontPage::leftJoin('front_page_translations', 'front_pages.id', '=',
            'front_page_translations.front_page_id')
            ->select('name', 'first_text', 'second_text')
            ->where('locale', '=', app()->getLocale())
            ->where('front_page_id', '=', 1)
            ->first();
        
        
        $integrated_solutions = FrontPage::leftJoin('front_page_translations', 'front_pages.id', '=',
            'front_page_translations.front_page_id')
            ->select('name', 'first_text', 'second_text', 'quote', 'button')
            ->where('locale', '=', app()->getLocale())
            ->where('front_page_id', '=', 2)
            ->first();
        
        $integrated_solutions_industries = Industry::leftJoin('industry_translations', 'industries.id', '=',
            'industry_translations.industry_id')
            ->select('industries.id', 'industries.online', 'name', 'slug')
            ->where('locale', '=', app()->getLocale())
            ->whereIn('industries.id', array(2, 12, 4, 6))
            ->orderByRaw('FIELD(industries.id,2,12,4,6)')
            ->get();
        
        
        $consultancy = FrontPage::leftJoin('front_page_translations', 'front_pages.id', '=',
            'front_page_translations.front_page_id')
            ->select('name', 'first_text', 'quote')
            ->where('locale', '=', app()->getLocale())
            ->where('front_page_id', '=', 3)
            ->first();
        
        $consultancy_references = Reference::leftJoin('reference_translations', 'references.id', '=',
            'reference_translations.reference_id')
            ->select('references.id', 'references.online', 'name', 'slug')
            ->where('locale', '=', app()->getLocale())
            ->whereIn('references.id', array(26, 2, 4, 3, 28, 27))
            ->orderByRaw('FIELD(references.id,26,2,4,3,28,27)')
            ->get();;
        
        
        $maintenance = FrontPage::leftJoin('front_page_translations', 'front_pages.id', '=',
            'front_page_translations.front_page_id')
            ->select('name', 'first_text', 'second_text', 'quote', 'button')
            ->where('locale', '=', app()->getLocale())
            ->where('front_page_id', '=', 4)
            ->first();
        
        
        $references = FrontPage::leftJoin('front_page_translations', 'front_pages.id', '=',
            'front_page_translations.front_page_id')
            ->select('name', 'button')
            ->where('locale', '=', app()->getLocale())
            ->where('front_page_id', '=', 5)
            ->first();
        
        $references_industries = Industry::leftJoin('industry_translations', 'industries.id', '=',
            'industry_translations.industry_id')
            ->select('industries.id', 'industries.online', 'name', 'slug')
            ->where('locale', '=', app()->getLocale())
            ->whereIn('industries.id', array(2, 12, 4, 6))
            ->orderByRaw('FIELD(industries.id,2,12,4,6)')
            ->get();
        
        $references_references = Reference::leftJoin('reference_translations', 'references.id', '=',
            'reference_translations.reference_id')
            ->select('references.id', 'references.online', 'name', 'slug')
            ->where('locale', '=', app()->getLocale())
            ->whereIn('references.id', array(3, 5, 6, 7, 10, 12, 14, 15))
            ->orderByRaw('FIELD(references.id,3,5,6,7,10,12,14,15)')
            ->get();;;
        
        
        $about_us = FrontPage::leftJoin('front_page_translations', 'front_pages.id', '=',
            'front_page_translations.front_page_id')
            ->select('name', 'first_text', 'second_text')
            ->where('locale', '=', app()->getLocale())
            ->where('front_page_id', '=', 6)
            ->first();
        
        $contact_us = FrontPage::leftJoin('front_page_translations', 'front_pages.id', '=',
            'front_page_translations.front_page_id')
            ->select('name', 'first_text', 'button')
            ->where('locale', '=', app()->getLocale())
            ->where('front_page_id', '=', 7)
            ->first();
        
        $metaData = getStaticMetaData([
            'meta_title' => trans('seo.home.title'),
            'meta_description' => trans('seo.home.description'),
            'meta_image_url' => url('').'/img/home/s6/xl-min.jpg',
            'meta_image_width' => 960,
            'meta_image_height' => 815,
        ]);
        
        return view('welcome',
            compact('hero', 'integrated_solutions', 'integrated_solutions_industries', 'consultancy', 'maintenance',
                'references', 'about_us', 'contact_us', 'consultancy_references', 'references_industries',
                'references_references', 'metaData'));
    }
}
