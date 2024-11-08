<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $application_slug = $request->slug;

        $application = Application::whereTranslation('slug', $application_slug)->whereTranslation('locale',app()->getLocale())->first();

        $productIds = Application::find($application->id)
            ->products()
            ->pluck('id')
            ->toArray();

        $categories = Category::whereHas('products', function ($query) use ($productIds) {
            $query->whereIn('products.id', $productIds);
        })
            ->with('product_image')
            ->orderByTranslation('name')
            ->get();

        if($categories->count() == 1)
        {
            $category = $categories->first();
            $category_id = $category->id;

            $products = Product::with('translations') // Eager load translations
                ->whereHas('categories', function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                })
                ->get();

            if($products->count() == 1)
            {
                $product = $products->first();
                return view('product', compact( 'category',  'product'));
            }

            return view('category', compact('application', 'categories', 'category', 'products'));
        }

        return view('categories', compact('application', 'categories'));
    }
}
