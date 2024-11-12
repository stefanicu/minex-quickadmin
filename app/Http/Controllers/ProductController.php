<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\Product;
use App\Models\Productfile;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $currentLocale = app()->getLocale();

        $product = Product::with('translations','media')
            ->leftJoin('product_translations','products.id','=','product_translations.product_id')
            ->where('products.online','=',1)
            ->where('product_translations.online','=',1)
            ->where('product_translations.slug','=',$request->slug)
            ->where('product_translations.locale','=',$currentLocale)
            ->select(sprintf('%s.*', (new Product)->table))
            ->first();


        if($product === null)
        {
            return redirect(url(''));
        }

        $references = $product->references()
            ->with(['translations' => function ($query) use ($currentLocale) {
                $query->where('locale', $currentLocale);
            }])
            ->get();

        $categoryId = session('category_id');
        $similar_products = Product::whereHas('categories', function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        })
            ->where('id', '!=', $product->id)
            ->get();

        $files = Productfile::where('product_id', $product->id)
            ->whereRaw("FIND_IN_SET(?, languages)", [$currentLocale])
            ->get();

        $referrer = $request->headers->get('referer');

        if(session()->has('application_id') &&
            !str_contains($referrer, 'search?search') &&
            !str_contains($referrer, 'partner') &&
            !str_contains($referrer, 'parteneri') &&
            $referrer !== null )
        {
            $application = Application::where('id',session('application_id'))->with('translations')->first();
        }
        else
        {
            $application = Product::find($product->id)
                ->applications() // Access the many-to-many relationship
                ->with('translations') // Eager load translations for each application
                ->first();
        }

        if(session()->has('category_id') &&
            !str_contains($referrer, 'search?search') &&
            !str_contains($referrer, 'partner') &&
            !str_contains($referrer, 'parteneri') &&
            $referrer !== null)
        {
            $category = Category::where('id',session('category_id'))->with('translations')->first();
        }
        else
        {
            $category = Product::find($product->id)
                ->categories() // Access the many-to-many relationship
                ->with('translations') // Eager load translations for each application
                ->first();
        }

        $categories = Category::whereHas('products', function($query) use ($application) {
            // Filter products that belong to the specified category
            $query->whereHas('applications', function($query) use ($application) {
                $query->where('applications.id', $application->id);
            });
        })
            ->with('translations') // Load translations for each application
            ->orderByTranslation('name')
            ->withCount('products') // Adds a `products_count` attribute to each category
            ->get();

//        $productIds = Application::find($application->id)
//            ->products()
//            ->whereHas('translations', function ($query) use ($currentLocale) {
//                $query->where('locale', $currentLocale);
//            })
//            ->pluck('id')
//            ->toArray();
//
//        $categories = Category::whereHas('products', function ($query) use ($productIds, $currentLocale) {
//            $query->whereIn('products.id', $productIds);
//        })
//            ->with('product_main_image')
//            ->orderByTranslation('name')
//            ->withCount('products') // Adds a `products_count` attribute to each category
//            ->get();

        return view('product', compact( 'product','references','files','similar_products','application','category','categories','referrer'));
    }
}
