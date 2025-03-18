<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Filter;
use App\Models\FilterTranslation;
use Illuminate\Http\Request;

class DropDownController extends Controller
{
    public function getCategories(Request $request)
    {
        $categories_ids = Category::where('application_id', $request->application_id)->pluck('id')->toArray();
        $categories = CategoryTranslation::whereIn('category_id', $categories_ids)
            ->where('locale', app()->getLocale()) // Filter by current locale
            ->get();
        return response()->json($categories);
    }
    
    public function getFilters(Request $request)
    {
        $filters_ids = Filter::where('category_id', $request->category_id)->pluck('id')->toArray();
        $filters = FilterTranslation::whereIn('filter_id', $filters_ids)
            ->where('locale', app()->getLocale()) // Filter by current locale
            ->get();
        return response()->json($filters);
    }
}