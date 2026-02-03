<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait TranslateLinks
{
    /**
     * Identifică link-urile dintr-un text HTML și le traduce slug-urile pe baza bazei de date.
     */
    public function translateLinksInHtml(string $html, string $targetLocale, string $sourceLocale): string
    {
        if (empty($html)) {
            return $html;
        }
        
        // Prevenim erorile de parsare pentru HTML parțial (fără root element)
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        // Încărcăm cu UTF-8
        $dom->loadHTML('<?xml encoding="UTF-8"><div>'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        
        $links = $dom->getElementsByTagName('a');
        $modified = false;
        
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (empty($href)) {
                continue;
            }
            
            $newHref = $this->translateUrl($href, $targetLocale, $sourceLocale);
            if ($newHref !== $href) {
                $link->setAttribute('href', $newHref);
                $modified = true;
            }
        }
        
        if ($modified) {
            // Extragem conținutul din interiorul div-ului adăugat de noi
            $root = $dom->getElementsByTagName('div')->item(0);
            $newHtml = '';
            foreach ($root->childNodes as $child) {
                $newHtml .= $dom->saveHTML($child);
            }
            return $newHtml;
        }
        
        return $html;
    }
    
    /**
     * Traduce un URL individual.
     */
    private function translateUrl(string $url, string $targetLocale, string $sourceLocale): string
    {
        // Ignorăm link-urile externe, mailto, tel, ancore interne
        if (preg_match('/^(https?:\/\/|mailto:|tel:|#)/i', $url)) {
            // Dacă e link extern, verificăm dacă este spre domeniul nostru (opțional)
            // Pentru moment presupunem că link-urile relative sau cele care încep cu / sunt interne
            if ( ! preg_match('/^'.preg_quote(config('app.url'), '/').'/i', $url)) {
                return $url;
            }
            // Dacă e link absolut spre site-ul nostru, îl transformăm în relativ pentru procesare
            $url = str_replace(config('app.url'), '', $url);
        }
        
        // Eliminăm prefixul de limbă dacă există (ex: /ro/...)
        $path = ltrim($url, '/');
        $parts = explode('/', $path);
        
        if (count($parts) > 0 && ($parts[0] === 'ro' || $parts[0] === 'en' || $parts[0] === 'bg')) {
            array_shift($parts);
            $pathWithoutLocale = implode('/', $parts);
        } else {
            $pathWithoutLocale = $path;
        }
        
        // Încercăm să identificăm resursa pe baza segmentelor de URL
        // Structuri posibile conform web.php:
        // blog/{slug}
        // brands/{slug}
        // references/{slug}
        // {app_slug}/{cat_slug}
        // {app_slug}/{cat_slug}/{prod_slug}
        // {slug} (pentru pagini)
        
        $newPath = $this->resolveNewPath($pathWithoutLocale, $targetLocale, $sourceLocale);
        
        if ($newPath) {
            return '/'.$targetLocale.'/'.ltrim($newPath, '/');
        }
        
        // Dacă nu am reușit să traducem slug-ul, returnăm URL-ul cu noul locale
        return '/'.$targetLocale.'/'.$pathWithoutLocale;
    }
    
    private function resolveNewPath(string $path, string $targetLocale, string $sourceLocale): ?string
    {
        $parts = explode('/', $path);
        
        // Cazul Blog, Brands, References (au prefixe fixe în trans('slugs.X'))
        // Deoarece nu știm exact traducerile segmentelor fixe fără a interoga trans(),
        // dar știm structura din web.php.
        
        $blogSlugSource = trans('slugs.blog', [], $sourceLocale);
        $blogSlugTarget = trans('slugs.blog', [], $targetLocale);
        if ($parts[0] === $blogSlugSource) {
            if (isset($parts[1])) {
                $newSlug = $this->translateSlug('blog_translations', 'blog_id', $parts[1], $targetLocale, $sourceLocale);
                return $blogSlugTarget.'/'.($newSlug ?: $parts[1]);
            }
            return $blogSlugTarget;
        }
        
        $brandsSlugSource = trans('slugs.brands', [], $sourceLocale);
        $brandsSlugTarget = trans('slugs.brands', [], $targetLocale);
        if ($parts[0] === $brandsSlugSource) {
            if (isset($parts[1])) {
                // Notă: Modelele Brand nu au slug în tabelul de traduceri conform analizei mele inițiale? 
                // Ba da, au in table 'brands' coloana 'slug' dar nu sunt traductibile?
                // Verific din nou: Brand model has translatedAttributes = ['online', 'offline_message', 'content'...] 
                // DAR in fillable are 'slug'. Deci slug-ul nu e traductibil la Brands? 
                // Dacă nu e traductibil, rămâne același.
                return $brandsSlugTarget.'/'.$parts[1];
            }
            return $brandsSlugTarget;
        }
        
        $refsSlugSource = trans('slugs.references', [], $sourceLocale);
        $refsSlugTarget = trans('slugs.references', [], $targetLocale);
        if ($parts[0] === $refsSlugSource) {
            if (isset($parts[1])) {
                $newSlug = $this->translateSlug('reference_translations', 'reference_id', $parts[1], $targetLocale, $sourceLocale);
                return $refsSlugTarget.'/'.($newSlug ?: $parts[1]);
            }
            return $refsSlugTarget;
        }
        
        // Cazul Products/Categories: {app_slug}/{cat_slug}/{prod_slug}
        if (count($parts) === 3) {
            $appSlug = $this->translateSlug('application_translations', 'application_id', $parts[0], $targetLocale, $sourceLocale);
            $catSlug = $this->translateSlug('category_translations', 'category_id', $parts[1], $targetLocale, $sourceLocale);
            $prodSlug = $this->translateSlug('product_translations', 'product_id', $parts[2], $targetLocale, $sourceLocale);
            return ($appSlug ?: $parts[0]).'/'.($catSlug ?: $parts[1]).'/'.($prodSlug ?: $parts[2]);
        }
        
        // Cazul Application/Category: {app_slug}/{cat_slug}
        if (count($parts) === 2) {
            $appSlug = $this->translateSlug('application_translations', 'application_id', $parts[0], $targetLocale, $sourceLocale);
            $catSlug = $this->translateSlug('category_translations', 'category_id', $parts[1], $targetLocale, $sourceLocale);
            return ($appSlug ?: $parts[0]).'/'.($catSlug ?: $parts[1]);
        }
        
        // Cazul Page sau Application singular: {slug}
        if (count($parts) === 1 && ! empty($parts[0])) {
            // Încercăm întâi Application
            $appSlug = $this->translateSlug('application_translations', 'application_id', $parts[0], $targetLocale, $sourceLocale);
            if ($appSlug) {
                return $appSlug;
            }
            
            // Apoi Page
            $pageSlug = $this->translateSlug('page_translations', 'page_id', $parts[0], $targetLocale, $sourceLocale);
            if ($pageSlug) {
                return $pageSlug;
            }
            
            // Apoi Industry (deși nu am văzut rută directă în web.php, dar e bine de avut)
            $indSlug = $this->translateSlug('industry_translations', 'industry_id', $parts[0], $targetLocale, $sourceLocale);
            if ($indSlug) {
                return $indSlug;
            }
        }
        
        return null;
    }
    
    private function translateSlug(string $table, string $foreignKey, string $slug, string $targetLocale, string $sourceLocale): ?string
    {
        try {
            // Găsim ID-ul resursei pe baza slug-ului în limba sursă
            $resource = DB::table($table)
                ->where('slug', $slug)
                ->where('locale', $sourceLocale)
                ->first();
            
            if ($resource) {
                $id = $resource->{$foreignKey};
                // Găsim slug-ul în limba țintă
                $translated = DB::table($table)
                    ->where($foreignKey, $id)
                    ->where('locale', $targetLocale)
                    ->first();
                
                return $translated->slug ?? null;
            }
        } catch (\Exception $e) {
            Log::error("Error translating slug in table $table: ".$e->getMessage());
        }
        
        return null;
    }
}
