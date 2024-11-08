<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $category_slug = $request->slug;

        $application = Application::find($request->id);
        $applicationId = $application->id;

        $category = Category::whereTranslation('slug', $category_slug)->whereTranslation('locale',app()->getLocale())->first();

        $products = Category::find($category->id)
            ->products()
            ->orderByTranslation('name')
            ->get();

        $categories = Category::whereHas('products')
            ->whereHas('applications', function($query) use ($applicationId) {
                $query->where('applications.id', $applicationId);
            })
            ->orderByTranslation('name')
            ->withCount('products') // Adds a `products_count` attribute to each category
            ->get();

        if($products->count() == 1)
        {
            $product = $products->first();
            return view('product', compact( 'category',  'product'));
        }

        return view('category', compact( 'category', 'categories', 'products', 'application'));
    }
}
