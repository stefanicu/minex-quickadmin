<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use App\Models\Reference;

class ReferencesController extends Controller
{
    public function index()
    {
        $references = Reference::with('media', 'translations')
            ->leftJoin('reference_translations', 'references.id', '=', 'reference_translations.reference_id')
            ->select('references.id', 'reference_translations.name', 'reference_translations.slug',
                'references.industries_id')
            ->where('references.online', '=', 1)
            ->where('reference_translations.online', '=', 1)
            ->where('locale', '=', app()->getLocale())
            ->orderBy('reference_translations.name')->get();
        
        $industries_in_tab = array(12, 4, 6, 8);
        
        $ind_ids = implode(",", $industries_in_tab);
        
        $industries = Industry::whereIn('id', $industries_in_tab)->orderByRaw("FIELD(id, $ind_ids)")->get();
        
        $slug = trans('pages_slugs.references', [], app()->getLocale());
        
        $metaData = getStaticMetaData([
            'meta_title' => trans('seo.references.title'),
            'meta_description' => trans('seo.references.description'),
        ]);
        
        return view('references', compact('references', 'slug', 'industries', 'industries_in_tab', 'metaData'));
    }
    
    public static function industries_id_in_tab()
    {
        return array(12, 4, 6, 8);
    }
}
