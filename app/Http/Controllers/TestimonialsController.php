<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Testimonial;

class TestimonialsController extends Controller
{
    public function index()
    {
        $applications = Application::all();
        $testimonials = Testimonial::leftJoin('testimonial_translations','testimonials.id', '=', 'testimonial_translations.testimonial_id')
            ->select('testimonial_id','company')
            ->where('locale','=',app()->getLocale())
            ->where('testimonial_translations.online','=',1)
            ->orderBy('company')->get();

        return view('testimonials', compact('applications','testimonials'));
    }
}
