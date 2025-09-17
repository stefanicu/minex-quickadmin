<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Productfile;
use App\Traits\HasMetaData;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HasMetaData;
    
    public function index(Request $request)
    {
        $currentLocale = app()->getLocale();
        
        $product_slug = $request->prod_slug;
        
        $product = Product::leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->with('translations', 'media')
            ->where('product_translations.online', '=', 1)
            ->where('product_translations.slug', '=', $product_slug)
            ->where('product_translations.locale', '=', $currentLocale)
            ->select(sprintf('%s.*', (new Product)->table))
            ->first();
        
        $brandOfflineMessage = trans('pages.no_brand_default_message');
        
        $brand = null;
        if (isset($product->brand_id)) {
            $brand = Brand::find($product->brand_id);
        }
        
        if ($brand) {
                $brand->offline_message ?? $brandOfflineMessage = $brand->offline_message;
        }
        
        if ( ! $product) {
            abort(404);
        }
        
        $application = Application::with('translations')->find($product->application_id);
        
        $category = Category::with('translations')->find($product->category_id);
        
        // Prevent error if application_id is null
        $application_slug = $request->app_slug ?? optional($application)->slug;
        
        // Prevent error if category_id is null
        $category_slug = $request->cat_slug ?? optional($category)->slug;
        
        
        $products = Product::where('category_id', $category->id)
            ->with('media')
            ->whereHas('translations', function ($query) use ($currentLocale) {
                $query->where('locale', $currentLocale)
                    ->where('online', 1);
            })
            ->orderByTranslation('name')
            ->get();
        
        $categories = Category::where('application_id', $application->id) // Direct filter using foreign key
        ->with('translations') // Load category translations
        ->orderByTranslation('name') // Order by translated name
        ->withCount([
            'products as products_count' => function ($query) use ($currentLocale, $application) {
                $query->where('application_id', $application->id) // Ensure products belong to the current application
                ->whereHas('translations', function ($query) use ($currentLocale) {
                    $query->where('locale', $currentLocale)
                        ->where('online', 1);
                });
            }
        ])
            ->having('products_count', '>', 0) // Only categories with at least one product
            ->get();
        
        
        $references = $product->references()
            ->whereHas('translations', function ($query) use ($currentLocale) {
                $query->where('locale', $currentLocale);
            })
            ->with('translations') // Load translations after filtering
            ->get();
        
        $files = Productfile::where('product_id', $product->id)
            ->whereRaw("FIND_IN_SET(?, languages)", [$currentLocale])
            ->get();
        
        $referrer = $request->headers->get('referer');
        
        $similar_products = Product::select('products.*', 'product_translations.name')
            ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->where('products.category_id', $category->id) // Direct filter instead of whereHas()
            ->where('product_translations.online', 1)
            ->where('product_translations.locale', app()->getLocale())
            ->where('products.id', '!=', $product->id) // Exclude the current product
            ->whereNotNull('products.brand_id') // Ensure brand exists
            ->get();
        
        $app_slugs = null;
        $cat_slugs = null;
        $prod_slugs = null;
        $canonical_product_page_brand_slugs = null;
        foreach (config('panel.available_languages') as $locale => $language) {
            $app_slugs[$locale] = $application->translate($locale)->slug ?? $application->translate('en')->slug;
            $cat_slugs[$locale] = $category->translate($locale)->slug ?? $category->translate('en')->slug;
            $prod_slugs[$locale] = $product->translate($locale)->slug ?? $product->id;
            if (isset($brand->slug)) {
                $canonical_product_page_brand_slugs[$locale] = $brand->slug;
            }
        }
        
        $metaData = $this->getMetaData($product);
        
        return view(
            'product',
            compact(
                'product',
                'references',
                'files',
                'similar_products',
                'application',
                'category',
                'categories',
                'referrer',
                'brand',
                'brandOfflineMessage',
                'app_slugs',
                'cat_slugs',
                'prod_slugs',
                'canonical_product_page_brand_slugs',
                'metaData'
            )
        );
    }
}
