<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Reference;
use App\Traits\HasMetaData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    use HasMetaData;
    
    public function index(Request $request)
    {
        $reference_slug = $request->slug;
        
        $reference = Reference::with('translations', 'media')
            ->leftJoin('reference_translations', 'references.id', '=', 'reference_translations.reference_id')
            ->select('references.id', 'reference_translations.name', 'reference_translations.slug',
                'references.industries_id')
            ->where('reference_translations.online', '=', 1)
            ->where('reference_translations.slug', '=', $reference_slug)
            ->where('locale', '=', app()->getLocale())
            ->select(sprintf('%s.*', (new Reference)->table), 'reference_translations.name as name',
                'reference_translations.slug as slug')
            ->first();
        
        
        if ( ! $reference) {
            abort(404);
        }
        
        $references = Reference::with('translations', 'media')
            ->leftJoin('reference_translations', 'references.id', '=', 'reference_translations.reference_id')
            ->select('references.id', 'reference_translations.name', 'reference_translations.slug',
                'references.industries_id')
            ->where('references.online', '=', 1)
            ->where('references.industries_id', '=', $reference->industries_id)
            ->where('references.id', '<>', $reference->id)
            ->where('reference_translations.online', '=', 1)
            ->where('locale', '=', app()->getLocale())
            ->select(sprintf('%s.*', (new Reference)->table), 'reference_translations.name as name',
                'reference_translations.slug as slug')
            ->orderBy('reference_translations.name')
            ->get();
        
        $products = Product::with(['translations', 'media', 'brand'])
            ->whereHas('references', function (Builder $query) use ($reference) {
                $query->where('reference_id', $reference->id);
            })
            ->leftJoin('product_translations', function ($join) {
                $join->on('products.id', '=', 'product_translations.product_id')
                    ->where('product_translations.locale', app()->getLocale())
                    ->where('product_translations.online', 1);
            })
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('application_translations', function ($join) {
                $join->on('products.application_id', '=', 'application_translations.application_id')
                    ->where('application_translations.locale', app()->getLocale());
            })
            ->leftJoin('category_translations', function ($join) {
                $join->on('products.category_id', '=', 'category_translations.category_id')
                    ->where('category_translations.locale', app()->getLocale());
            })
            ->where('products.online', 1)
            ->select(
                'products.*',
                'product_translations.name as name',
                'product_translations.slug as slug',
                'brands.name as brand_name',
                'brands.slug as brand_slug',
                'application_translations.slug as application_slug',
                'category_translations.slug as category_slug'
            )
            ->get();
        
        $slugs = null;
        foreach (config('translatable.locales') as $locale) {
            $slug_reference = $reference->translate($locale)->slug ?? $reference->id;
            $slugs[$locale] = $slug_reference;
        }
        
        $metaData = $this->getMetaData($reference);
        
        return view('reference', compact('references', 'reference', 'products', 'slugs', 'metaData'));
    }
}
