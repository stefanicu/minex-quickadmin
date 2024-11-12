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

        session(['application_id' => $application->id]);

        if(!$application) {
            $application = Application::whereTranslation('slug', $application_slug)->whereTranslation('locale','en')->first();
        }

        $currentLocale = app()->getLocale();

        $productIds = Application::find($application->id)
            ->products()
            ->whereHas('translations', function ($query) use ($currentLocale) {
                $query->where('locale', $currentLocale);
            })
            ->pluck('products.id')
            ->toArray();

        $categories = Category::with('product_main_image','product_main_image.media')
            ->whereHas('products', function ($query) use ($productIds) {
                $query->whereIn('products.id', $productIds);
            })
            ->orderByTranslation('name')
            ->get();

        if($categories->count() == 1)
        {
            $category = $categories->first();
            $category_id = $category->id;

            session(['category_id' => $category->id]);

            $products = Product::with(['translations' => function ($query) use ($currentLocale) {
                    $query->where('locale', $currentLocale);
                }])
                ->whereHas('translations', function ($query) use ($currentLocale) {
                    $query->where('locale', $currentLocale);
                })
                ->whereHas('categories', function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                })
                ->get();

            if($products->count() == 1)
            {
                $product = $products->first();
                return redirect(route('product.index', ['slug' => $product->slug]));
            }

            return redirect(route('category.index', ['slug' => $category->slug]));
        }

        return view('categories', compact('application', 'categories'));
    }
}
