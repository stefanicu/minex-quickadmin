<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\TranslateWithQueue;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslationGranularController extends Controller
{
    use TranslateWithQueue;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('translation_center_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $locale = $request->input('language');
        $modelTranslation = $request->input('model_translation');
        $foreignKey = $request->input('foreign_key');;
        $id = $request->input('id');
        
        $this->translateQueueByColumns($modelTranslation, $foreignKey, $locale, $id);
        
        return redirect()->back()->with('success', __('Translations updated successfully for ').$locale);
    }
}
