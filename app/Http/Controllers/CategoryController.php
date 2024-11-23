<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $category = Category::whereTranslation('slug', $request->slug)->whereTranslation('locale',app()->getLocale())->first();

        session(['category_id' => $category->id]);

        $referrer = $request->headers->get('referer');

        if(session()->has('application_id') && $referrer !== null)
        {
            $application = Application::where('id',session('application_id'))->with('translations')->first();
        }
        else
        {
            $application = Application::whereHas('products', function($query) use ($category) {
                // Filter products that belong to the specified category
                $query->whereHas('categories', function($query) use ($category) {
                    $query->where('categories.id', $category->id);
                });
            })
                ->with('translations') // Load translations for each application
                ->first();
        }

        $currentLocale = app()->getLocale();

        $products = Category::find($category->id)
            ->products()
            ->with('media')
            ->whereHas('translations', function ($query) use ($currentLocale) {
                $query->where('locale', $currentLocale)
                    ->where('online', 1);  // Ensure only translations with 'online' = 1 are included
            })
            ->orderByTranslation('name')
            ->get();


        $productIds = Application::find($application->id)
            ->products()
            ->whereHas('translations', function ($query) use ($currentLocale) {
                $query->where('locale', $currentLocale);
            })
            ->pluck('id')
            ->toArray();

        $categories = Category::whereHas('products', function($query) use ($application) {
            // Filter products that belong to the specified category
            $query->whereHas('applications', function($query) use ($application) {
                $query->where('applications.id', $application->id);
            });
        })
            ->with('translations') // Load translations for each application
            ->orderByTranslation('name')
            ->withCount(['products as products_count' => function ($query) use ($currentLocale) {
                $query->whereHas('translations', function ($query) use ($currentLocale) {
                    $query->where('locale', $currentLocale)
                        ->where('online', 1);
                });
            }])
            ->having('products_count', '>', 0) // Filter out categories with zero products
            ->get();

        if($categories->count() === 0){
            return redirect(url(''));
        }

        if($products->count() == 1)
        {
            $product = $products->first();
            return redirect(route('product.index', ['slug' => $product->slug]));
        }

        return view('category', compact( 'category', 'categories', 'products', 'application'));
    }
}
