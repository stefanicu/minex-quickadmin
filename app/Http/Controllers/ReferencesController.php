<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Reference;

class ReferencesController extends Controller
{
    public function index()
    {
        $applications = Application::all();
        $references = Reference::leftJoin('reference_translations','references.id', '=', 'reference_translations.reference_id')
            ->select('reference_id','name','slug')
            ->where('locale','=',app()->getLocale())
            ->where('reference_translations.online','=',1)
            ->orderBy('name')->get();

        return view('references', compact('applications','references'));
    }
}
