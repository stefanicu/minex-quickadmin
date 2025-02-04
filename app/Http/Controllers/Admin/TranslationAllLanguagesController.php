<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\TranslateWithQueue;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslationAllLanguagesController extends Controller
{
    use TranslateWithQueue;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('translation_center_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $modelTranslation = $request->input('model_translation');
        $foreignKey = $request->input('foreign_key');
        $id = $request->input('id');
        
        $locales = config('translatable.locales');
        $locales = array_diff($locales, ['en']); // Exclude the unwanted columns
        
        foreach ($locales as $locale) {
            $this->translateQueueByColumns($modelTranslation, $foreignKey, $locale, $id);
        }
        
        return redirect()->back()->with('success', __('Translations are being processed in the background for All Languages'));
    }
}
