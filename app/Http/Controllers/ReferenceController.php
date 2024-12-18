<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    public function index(Request $request)
    {
        $reference_id = Reference::whereTranslation('slug', $request->slug)->first()->id;
        
        $reference = Reference::with('translations', 'media')
            ->leftJoin('reference_translations', 'references.id', '=', 'reference_translations.reference_id')
            ->select('references.id', 'reference_translations.name', 'reference_translations.slug',
                'references.industries_id')
            ->where('references.online', '=', 1)
            ->where('reference_translations.online', '=', 1)
            ->where('reference_translations.reference_id', '=', $reference_id)
            ->where('locale', '=', app()->getLocale())
            ->select(sprintf('%s.*', (new Reference)->table), 'reference_translations.name as name',
                'reference_translations.slug as slug')
            ->first();
        
        if ( ! $reference) {
            $reference = Reference::with('translations', 'media')
                ->leftJoin('reference_translations', 'references.id', '=', 'reference_translations.reference_id')
                ->select('references.id', 'reference_translations.name', 'reference_translations.slug',
                    'references.industries_id')
                ->where('references.online', '=', 1)
                ->where('reference_translations.online', '=', 1)
                ->where('reference_translations.reference_id', '=', $reference_id)
                ->select(sprintf('%s.*', (new Reference)->table), 'reference_translations.name as name',
                    'reference_translations.slug as slug')
                ->first();
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
        
        $products = Product::with('translations', 'media')
            ->whereHas('references', function (Builder $query) use ($reference) {
                $query->where('reference_id', '=', $reference->id);
            })
            ->leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->where('products.online', '=', 1)
            ->where('product_translations.online', '=', 1)
            ->where('product_translations.locale', '=', app()->getLocale())
            ->select(sprintf('%s.*', (new Product)->table), 'product_translations.name as name',
                'product_translations.slug as slug')
            ->get();
        
        return view('reference', compact('references', 'reference', 'products'));
    }
}
