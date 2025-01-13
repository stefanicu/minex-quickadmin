<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;

class TestimonialsController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::leftJoin('testimonial_translations', 'testimonials.id', '=', 'testimonial_translations.testimonial_id')
            ->where('testimonial_translations.online', '=', 1)
            ->where('locale', '=', app()->getLocale())
            ->select('testimonials.id as id', 'testimonial_translations.company')
            ->orderBy('company')->get();
        
        $metaData = getStaticMetaData([
            'meta_title' => trans('seo.testimonials_title'),
            'meta_description' => trans('seo.testimonials_description'),
        ]);
        
        return view('testimonials', compact('testimonials', 'metaData'));
    }
}
